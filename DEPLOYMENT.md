# Deploy Vyndra from Git

Vyndra is a WordPress/WooCommerce store. GitHub Pages cannot run its PHP code or database. Use WordPress hosting with PHP, MySQL/MariaDB, and HTTPS. A push to GitHub stores the code; it does not make the store live until the hosting account is connected to a deployment process.

## What this repository contains

Only the custom storefront must-use plugin is versioned:

- `wp-content/mu-plugins/vyndra-storefront.php`
- `wp-content/mu-plugins/vyndra-storefront/`

The WordPress core, third-party plugins and themes, `wp-config.php`, database, and `wp-content/uploads` are outside Git. This avoids publishing credentials, customer/order data, media, and commercial plugin packages. Install or migrate the exact plugin/theme versions the current site uses, including WooCommerce, Elementor, and any licensed plugins, through the host or a separate approved migration. The custom storefront refers to existing products, images, Elementor settings, and WordPress pages by data in the database; copying code alone will not reproduce the website.

## Current Git setup

The local branch is `main`, with remote `https://github.com/anuragnegiee/vyndra.git`. Before the first push, check whether the GitHub repository already has a branch and preserve its history. Use a private repository unless you have reviewed everything intended for publication.

From this directory:

```powershell
git status --short
git add .gitignore DEPLOYMENT.md wp-content/mu-plugins/vyndra-storefront.php wp-content/mu-plugins/vyndra-storefront
git diff --cached --stat
git diff --cached --check
git commit -m "Add Vyndra storefront deployment source"
git push -u origin main
```

If GitHub already has commits, fetch and reconcile its history before committing or pushing. Never force-push the first release. Authenticate with Git Credential Manager or SSH; keep tokens and private keys out of this repository.

## First live migration

1. Confirm the host, live domain, WordPress document root, and whether the destination store already has orders or customer accounts. Back up its files and database before touching it.
2. Install WordPress and required plugins/themes on the host, or migrate the matching installation. Configure the live `wp-config.php` with host-specific database settings and fresh authentication salts.
3. Transfer `wp-content/uploads` separately and migrate the database to the agreed destination. Never overwrite an active store's newer orders or customer accounts with a local database.
4. Update the old site URL to the HTTPS live domain with a serialization-aware tool such as WP-CLI `search-replace`; run a dry run first. Raw SQL replacement can damage serialized Elementor and WordPress data.
5. Deploy the two custom storefront paths from the Git checkout into the live `wp-content/mu-plugins/` directory. Keep `.git` and this document outside the public web root. Do not use a whole-site copy with deletion enabled.
6. Regenerate Elementor CSS/data, resave permalinks, clear caches, and test the home, shop, product, cart, checkout, account, images, email, shipping, payments, and webhook flows before opening the store to customers.

## Make later Git pushes deploy

The exact deployment configuration depends on the host:

- **cPanel Git Version Control:** create a cPanel-managed repository outside `public_html`, confirm the real document-root path, then add a checked-in `.cpanel.yml` that copies only the two storefront paths to `wp-content/mu-plugins/`. A direct push to that cPanel repository triggers deployment. A push only to GitHub requires a separate pull/deploy step or automation.
- **SSH/VPS:** clone this repository outside the web root and use a host-specific release script or GitHub Actions workflow to copy only those two paths into the live `wp-content/mu-plugins/` directory after a push to `main`.

Do not configure either route with a guessed document root. The hosting provider, domain, absolute document-root path, deployment access, and status of any existing live store are needed before automatic deployment can be enabled.

Subsequent code releases should never reimport the local database. Coordinate dashboard plugin/theme updates with the live installation and back up the database before releases that run migrations.

References: [WordPress migration](https://developer.wordpress.org/advanced-administration/upgrade/migrating/), [cPanel Git deployment](https://docs.cpanel.net/knowledge-base/web-services/guide-to-git-deployment/), and [GitHub Pages](https://docs.github.com/en/pages/getting-started-with-github-pages/what-is-github-pages).
