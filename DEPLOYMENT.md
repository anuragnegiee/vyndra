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

## Hostinger hPanel deployment

Hostinger's current Git integration is under **Websites → Dashboard → Advanced → Git**. It connects to GitHub through OAuth, lets you select a repository and branch, and can automatically deploy subsequent pushes. The **Root directory** defaults to `public_html` and can be changed. Hostinger warns that changing or disconnecting the deployment repository can overwrite data in the target directory.

**Do not connect this `main` branch to `public_html` yet.** The branch contains only the custom storefront, while the live WordPress directory also needs core, plugins, `wp-config.php`, uploads, and other files. The repository's `wp-content/mu-plugins/` prefix also means it cannot be pointed directly at the live `mu-plugins` directory without preparing a separate deploy tree.

After the WordPress files, database, and media are migrated and the live destination is inspected, choose one of these safe setups:

1. Prepare a deployment branch whose root has only `vyndra-storefront.php` and `vyndra-storefront/`; point hPanel Git at that branch and the verified `public_html/wp-content/mu-plugins` directory, after confirming how hPanel handles existing files there.
2. If that target cannot be safely managed by hPanel Git, use a GitHub Actions release over SSH/SFTP that copies only those two paths to the confirmed WordPress root, without deleting other files.

The live domain, hosting plan, status of any existing store, and actual WordPress root are needed to select and validate the deployment target. Hostinger's [Git deployment guide](https://www.hostinger.com/support/1583302-how-to-deploy-a-git-repository-in-hostinger/) describes the current hPanel controls. Hostinger's [migration guide](https://www.hostinger.com/support/4455931-how-to-migrate-a-website-to-hostinger/) covers moving the files and database that Git does not contain.

Subsequent code releases should never reimport the local database. Coordinate dashboard plugin/theme updates with the live installation and back up the database before releases that run migrations.

References: [WordPress migration](https://developer.wordpress.org/advanced-administration/upgrade/migrating/) and [GitHub Pages](https://docs.github.com/en/pages/getting-started-with-github-pages/what-is-github-pages).
