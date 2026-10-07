#!/usr/bin/env python3
"""Deploy only the Vyndra must-use plugin to an existing WordPress site."""

from __future__ import annotations

import ftplib
import os
import ssl
from pathlib import Path

HOST = "147.93.17.234"
PORT = 21
DOMAIN = "vyndra.in"
ABSOLUTE_ROOT = "/home/u246079467/domains/vyndra.in/public_html"
LOCAL_ROOT = Path("wp-content/mu-plugins")
ENTRY = LOCAL_ROOT / "vyndra-storefront.php"
PLUGIN = LOCAL_ROOT / "vyndra-storefront"


def require_secret(name: str) -> str:
    value = os.environ.get(name, "")
    if not value:
        raise RuntimeError(f"Missing GitHub Actions secret: {name}")
    return value


def directory_entries(ftp: ftplib.FTP) -> set[str]:
    return {item.rsplit("/", 1)[-1] for item in ftp.nlst()}


def find_wordpress_root(ftp: ftplib.FTP) -> str:
    login_root = ftp.pwd()
    # Hostinger may expose the full path or chroot this site's FTP account.
    candidates = (
        ABSOLUTE_ROOT,
        f"/domains/{DOMAIN}/public_html",
        f"domains/{DOMAIN}/public_html",
        "/public_html",
        "public_html",
        ".",
    )
    for candidate in candidates:
        try:
            ftp.cwd(login_root)
            ftp.cwd(candidate)
            entries = directory_entries(ftp)
            if {"wp-load.php", "wp-content"}.issubset(entries):
                root = ftp.pwd()
                ftp.cwd("wp-content/plugins/woocommerce")
                ftp.cwd(root)
                return root
        except ftplib.error_perm:
            continue
    raise RuntimeError(
        "WordPress with WooCommerce was not found in this FTP account's "
        "vyndra.in public_html. Migrate the site before deploying the storefront."
    )


def ensure_directory(ftp: ftplib.FTP, root: str, relative: Path) -> None:
    ftp.cwd(root)
    for part in relative.parts:
        try:
            ftp.cwd(part)
        except ftplib.error_perm:
            ftp.mkd(part)
            ftp.cwd(part)


def upload_file(ftp: ftplib.FTP, root: str, source: Path, suffix: str) -> None:
    relative = source.relative_to(LOCAL_ROOT)
    target_dir = Path("wp-content/mu-plugins") / relative.parent
    ensure_directory(ftp, root, target_dir)
    target = relative.name
    temporary = f"{target}.deploying-{suffix}"
    with source.open("rb") as handle:
        ftp.storbinary(f"STOR {temporary}", handle)
    ftp.voidcmd("TYPE I")
    remote_size = ftp.size(temporary)
    if remote_size is not None and remote_size != source.stat().st_size:
        raise RuntimeError(f"Uploaded size differs for {relative}")
    try:
        ftp.rename(temporary, target)
    except ftplib.error_perm as exc:
        # Some FTP servers refuse RNTO when the destination already exists.
        # Move only that storefront file aside, then restore it on failure.
        if target not in directory_entries(ftp):
            raise RuntimeError(f"Could not install {relative}; temporary upload remains") from exc
        backup = f"{target}.previous-{suffix}"
        if backup in directory_entries(ftp):
            raise RuntimeError(f"Backup already exists for {relative}; refusing to overwrite it") from exc
        ftp.rename(target, backup)
        try:
            ftp.rename(temporary, target)
        except Exception:
            ftp.rename(backup, target)
            raise
    print(f"Deployed wp-content/mu-plugins/{relative.as_posix()}")


def main() -> None:
    if not ENTRY.is_file() or not PLUGIN.is_dir():
        raise RuntimeError("Storefront source files are missing from this checkout")
    files = sorted(item for item in PLUGIN.rglob("*") if item.is_file())
    if any(item.is_symlink() for item in files):
        raise RuntimeError("Symlinks are not permitted in the deployment source")
    files.append(ENTRY)  # Install the loader only after its dependencies.
    suffix = os.environ.get("GITHUB_SHA", "manual")[:12]
    context = ssl.create_default_context()
    # Connect to the IP supplied by hPanel. Its TLS certificate is for *.hstgr.io,
    # so verify the CA chain and that Hostinger name before sending credentials.
    context.check_hostname = False
    with ftplib.FTP_TLS(context=context, timeout=30) as ftp:
        ftp.connect(HOST, PORT)
        ftp.auth()
        certificate = ftp.sock.getpeercert()
        names = {value for kind, value in certificate.get("subjectAltName", ()) if kind == "DNS"}
        if "*.hstgr.io" not in names:
            raise RuntimeError("FTP server did not present the expected Hostinger certificate")
        ftp.login(require_secret("FTP_USERNAME"), require_secret("FTP_PASSWORD"))
        ftp.prot_p()
        root = find_wordpress_root(ftp)
        print("Verified WordPress and WooCommerce on the Hostinger target")
        for source in files:
            upload_file(ftp, root, source, suffix)
    print("Storefront code deployment complete; database and uploads were not changed")


if __name__ == "__main__":
    main()
