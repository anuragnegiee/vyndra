# Deploy Vyndra from Git

Vyndra is a WordPress/WooCommerce store. GitHub Pages cannot run its PHP code or database. Use WordPress hosting with PHP, MySQL/MariaDB, and HTTPS. A push to GitHub stores the code; it does not make the store live until the hosting account is connected to a deployment process.

## What this repository contains

Only the custom storefront must-use plugin is versioned:

- `wp-content/mu-plugins/vyndra-storefront.php`
- `wp-content/mu-plugins/vyndra-storefront/`

The WordPress core, third-party plugins and themes, `wp-config.php`, database, and `wp-content/uploads` are outside Git. This avoids publishing credentials, customer/order data, media, and commercial plugin packages. Install or migrate the exact plugin/theme versions the current site uses, including WooCommerce, Elementor, and any licensed plugins, through the host or a separate approved migration. The custom storefront refers to existing products, images, Elementor settings, and WordPress pages by data in the database; copying code alone will not reproduce the website.

## Push the code to GitHub

The local branch is `main`, with remote `https://github.com/anuragnegiee/vyndra.git`. The storefront code is committed locally. The first push needs your GitHub sign-in in a local terminal:

```powershell
git push -u origin main
```

For later changes, review the files being committed, then use `git add`, `git commit`, and `git push`. Use a private repository unless you have reviewed everything intended for publication. Never force-push or put tokens, private keys, database exports, or passwords in Git.

## First live migration

1. Confirm the host, live domain, WordPress document root, and whether the destination store already has orders or customer accounts. Back up its files and database before touching it.
2. Install WordPress and required plugins/themes on the host, or migrate the matching installation. Configure the live `wp-config.php` with host-specific database settings and fresh authentication salts.
3. Transfer `wp-content/uploads` separately and migrate the database to the agreed destination. Never overwrite an active store's newer orders or customer accounts with a local database.
4. Update the old site URL to the HTTPS live domain with a serialization-aware tool such as WP-CLI `search-replace`; run a dry run first. Raw SQL replacement can damage serialized Elementor and WordPress data.
5. Deploy the two custom storefront paths from the Git checkout into the live `wp-content/mu-plugins/` directory. Keep `.git` and this document outside the public web root. Do not use a whole-site copy with deletion enabled.
6. Regenerate Elementor CSS/data, resave permalinks, clear caches, and test the home, shop, product, cart, checkout, account, images, email, shipping, payments, and webhook flows before opening the store to customers.

## Hostinger deployment from GitHub Actions

The repository has a GitHub Actions workflow at `.github/workflows/deploy-storefront.yml`. A push to `main` that changes the storefront or deployment workflow triggers it; it can also be run manually from GitHub's Actions tab. The workflow reads the repository secrets `FTP_USERNAME` and `FTP_PASSWORD`. Their values are stored in GitHub settings and are never committed.

The workflow connects to Hostinger at `147.93.17.234` on port `21` using explicit FTPS. It verifies the server certificate before reading the GitHub credentials and encrypts file transfers. It locates the WordPress installation at `/home/u246079467/domains/vyndra.in/public_html` or its FTP account equivalent, then verifies that `wp-load.php` and WooCommerce exist. It uploads only `vyndra-storefront.php` and the `vyndra-storefront/` directory into `wp-content/mu-plugins/`. It does not delete remote files or update the database, media, WordPress core, or other plugins. If the destination is not an existing WordPress/WooCommerce installation, the workflow stops without uploading.

The hPanel FTP IP presents a certificate for Hostinger's `*.hstgr.io` domain, which the workflow checks along with the trusted certificate chain. If Hostinger changes that certificate identity, deployment stops before login so the connection can be reviewed. Do not copy any FTP password into this repository or share it in chat.

After pushing, check the **Deploy Vyndra storefront** run under the repository's **Actions** tab. A green run confirms only the custom code was uploaded. Complete the first migration steps above and verify the live store before accepting orders.

Subsequent code releases should never reimport the local database. Coordinate dashboard plugin/theme updates with the live installation and back up the database before releases that run migrations.

References: [WordPress migration](https://developer.wordpress.org/advanced-administration/upgrade/migrating/) and [GitHub Pages](https://docs.github.com/en/pages/getting-started-with-github-pages/what-is-github-pages).
