#!/usr/bin/env python3
"""Publish tracked WordPress code to an empty or existing Hostinger public_html."""

from __future__ import annotations

import ftplib
import os
import ssl
import subprocess
import threading
import uuid
from concurrent.futures import ThreadPoolExecutor, as_completed
from pathlib import Path

HOST = "147.93.17.234"
PORT = 21
DOMAIN = "vyndra.in"
ABSOLUTE_ROOT = "/home/u246079467/domains/vyndra.in/public_html"
WORKERS = 4
SITE_DIRECTORIES = {"wp-admin", "wp-includes"}
CONTENT_DIRECTORIES = {"mu-plugins", "plugins", "themes"}
ROOT_FILES = {".htaccess", "index.php", "license.txt", "readme.html", "xmlrpc.php"}


def require_secret(name: str) -> str:
    value = os.environ.get(name, "")
    if not value:
        raise RuntimeError(f"Missing GitHub Actions secret: {name}")
    return value


def site_file(path: Path) -> bool:
    parts = path.parts
    if len(parts) == 1:
        return path.name in ROOT_FILES or (path.name.startswith("wp-") and path.suffix == ".php")
    if parts[0] in SITE_DIRECTORIES:
        return True
    if parts[0] == "wp-content":
        return len(parts) == 2 and parts[1] == "index.php" or (
            len(parts) > 2 and parts[1] in CONTENT_DIRECTORIES
        )
    return False


def git_paths(command: list[str]) -> list[Path]:
    output = subprocess.run(command, check=True, stdout=subprocess.PIPE).stdout
    return [Path(os.fsdecode(name)) for name in output.split(b"\0") if name]


def files_to_upload() -> list[Path]:
    before = os.environ.get("GIT_BEFORE", "")
    if before and before != "0" * 40:
        command = ["git", "diff", "--name-only", "-z", "--diff-filter=ACMRT", before, "HEAD"]
    else:
        command = ["git", "ls-files", "-z"]
    files = [path for path in git_paths(command) if site_file(path) and path.is_file()]
    return sorted(files)


def connect() -> ftplib.FTP_TLS:
    context = ssl.create_default_context()
    # The hPanel IP presents a valid Hostinger certificate for *.hstgr.io.
    # Verify that identity and the CA chain before sending credentials.
    context.check_hostname = False
    ftp = ftplib.FTP_TLS(context=context, timeout=60)
    ftp.connect(HOST, PORT)
    ftp.auth()
    certificate = ftp.sock.getpeercert()
    names = {value for kind, value in certificate.get("subjectAltName", ()) if kind == "DNS"}
    if "*.hstgr.io" not in names:
        ftp.close()
        raise RuntimeError("FTP server did not present the expected Hostinger certificate")
    ftp.login(require_secret("FTP_USERNAME"), require_secret("FTP_PASSWORD"))
    ftp.prot_p()
    return ftp


def find_public_html(ftp: ftplib.FTP_TLS) -> str:
    login_root = ftp.pwd()
    candidates = (
        ABSOLUTE_ROOT,
        f"/domains/{DOMAIN}/public_html",
        f"domains/{DOMAIN}/public_html",
        "/public_html",
        "public_html",
    )
    for candidate in candidates:
        try:
            ftp.cwd(login_root)
            ftp.cwd(candidate)
            return ftp.pwd()
        except ftplib.error_perm:
            continue
    raise RuntimeError("Could not find vyndra.in public_html from this FTP account")


def ensure_directory(ftp: ftplib.FTP_TLS, root: str, directory: Path) -> None:
    ftp.cwd(root)
    for part in directory.parts:
        if part == ".":
            continue
        try:
            ftp.cwd(part)
        except ftplib.error_perm:
            try:
                ftp.mkd(part)
            except ftplib.error_perm:
                pass  # Another worker may have created it.
            ftp.cwd(part)


def upload_bytes(source: Path) -> bytes:
    data = source.read_bytes()
    if source.as_posix() == ".htaccess":
        old_base = b"RewriteBase /vyndra/"
        old_rule = b"RewriteRule . /vyndra/index.php [L]"
        if old_base not in data or old_rule not in data:
            raise RuntimeError("Local .htaccess no longer has the expected /vyndra/ rules")
        data = data.replace(old_base, b"RewriteBase /").replace(
            old_rule, b"RewriteRule . /index.php [L]"
        )
    return data


def upload_file(ftp: ftplib.FTP_TLS, root: str, source: Path, suffix: str) -> None:
    from io import BytesIO

    ensure_directory(ftp, root, source.parent)
    target = source.name
    temporary = f"{target}.deploying-{suffix}"
    data = upload_bytes(source)
    ftp.storbinary(f"STOR {temporary}", BytesIO(data))
    ftp.voidcmd("TYPE I")
    if ftp.size(temporary) != len(data):
        raise RuntimeError(f"Uploaded size differs for {source}")
    try:
        ftp.rename(temporary, target)
    except ftplib.error_perm as exc:
        # Preserve an existing code file if this server will not replace it by RNTO.
        entries = {name.rsplit("/", 1)[-1] for name in ftp.nlst()}
        if target not in entries:
            raise RuntimeError(f"Could not install {source}; temporary upload remains") from exc
        backup = f"{target}.previous-{suffix}-{uuid.uuid4().hex[:8]}"
        ftp.rename(target, backup)
        try:
            ftp.rename(temporary, target)
        except Exception:
            ftp.rename(backup, target)
            raise


def upload_chunk(files: list[Path], stop: threading.Event, suffix: str) -> int:
    count = 0
    with connect() as ftp:
        root = find_public_html(ftp)
        for source in files:
            if stop.is_set():
                break
            try:
                upload_file(ftp, root, source, suffix)
            except Exception:
                stop.set()
                raise
            count += 1
            if count % 250 == 0:
                print(f"Uploaded {count} files in this worker", flush=True)
    return count


def main() -> None:
    files = files_to_upload()
    if not files:
        print("No tracked WordPress code changed")
        return
    print(f"Uploading {len(files)} tracked WordPress files to {DOMAIN}", flush=True)
    suffix = os.environ.get("GITHUB_SHA", "manual")[:12]
    deferred_names = {"index.php", ".htaccess", "wp-content/mu-plugins/vyndra-storefront.php"}
    deferred = [item for item in files if item.as_posix() in deferred_names]
    bulk = [item for item in files if item.as_posix() not in deferred_names]
    stop = threading.Event()
    chunks = [bulk[index::WORKERS] for index in range(WORKERS)]
    total = 0
    with ThreadPoolExecutor(max_workers=WORKERS) as pool:
        futures = [pool.submit(upload_chunk, chunk, stop, suffix) for chunk in chunks if chunk]
        for future in as_completed(futures):
            total += future.result()
    if deferred:
        total += upload_chunk(deferred, stop, suffix)
    print(f"Uploaded {total} files. Database, wp-config.php, and uploads are separate.")


if __name__ == "__main__":
    main()
