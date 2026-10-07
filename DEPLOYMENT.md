# Deploy Vyndra to Hostinger

This repository now contains the complete active WordPress code: root PHP files, `wp-admin`, `wp-includes`, and active `wp-content/mu-plugins`, `wp-content/plugins`, and `wp-content/themes`. The deployment uploads these files to `/home/u246079467/domains/vyndra.in/public_html` through encrypted FTPS after a push to `main`.

The local `.htaccess` uses `/vyndra/` for XAMPP. The deployment changes those rewrite paths to `/` during upload for the live domain; the local file stays suitable for XAMPP.

## Files kept out of Git

`wp-config.php`, `.env` files, `wp-content/uploads`, database exports, logs, keys, caches, backups, and retired plugin/theme copies are excluded. They are data or local/server configuration, not active WordPress code. No password is stored in this repository. GitHub repository secrets `FTP_USERNAME` and `FTP_PASSWORD` are used by the deployment workflow.

## Push and deploy

The local branch is `main` and `origin` is `https://github.com/anuragnegiee/vyndra.git`. A push to `main` runs [the GitHub Actions workflow](.github/workflows/deploy-storefront.yml). The first run uploads all tracked WordPress code; later runs upload changed tracked code. It does not delete other files on the server.

```powershell
git push -u origin main
```

Complete the GitHub authentication prompt in your browser. In GitHub, open **Actions → Deploy Vyndra WordPress code** to watch the upload. The server is `147.93.17.234` on port `21`; the workflow checks Hostinger's TLS certificate before sending the FTP credentials and encrypts transfers. It tries the supplied absolute `public_html` path and Hostinger FTP account equivalents. If it cannot locate the directory, it stops without uploading.

This repository is publicly readable as of the initial full-code preparation. Review its visibility before pushing bundled third-party plugins; GitHub's [visibility settings](https://docs.github.com/en/repositories/managing-your-repositorys-settings-and-features/managing-repository-settings/setting-repository-visibility) are under **Settings → Danger Zone**.

## Finish the live WordPress installation

Code upload alone does not make the existing local site appear on the server. Hostinger still needs a MySQL/MariaDB database containing the WordPress pages, products, settings, and orders, a server-specific `wp-config.php` with its database credentials and fresh salts, and `wp-content/uploads` with product and page images. Keep these out of Git. If the destination is empty, create/import the database, transfer uploads separately, and create `wp-config.php` on the server. Do not copy the XAMPP credentials into production.

After import, update the old WordPress URL to `https://vyndra.in` with a serialization-aware tool, regenerate Elementor CSS, resave permalinks, clear caches, and check the home, shop, product, cart, checkout, and account pages. Set up payment, shipping, email, and webhooks before accepting orders. See [WordPress migration](https://developer.wordpress.org/advanced-administration/upgrade/migrating/) and [Hostinger migration](https://www.hostinger.com/support/4455931-how-to-migrate-a-website-to-hostinger/).
