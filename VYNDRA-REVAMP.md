# Vyndra storefront revamp

The new storefront runs on the original WordPress installation, Hello Elementor theme, WooCommerce catalog, and payment/shipping plugins. Its entry point is `wp-content/mu-plugins/vyndra-storefront.php`. No existing plugin or theme files were edited.

## Audit and content mapping

The audit was completed before building. A private detailed inventory is in `tools/vyndra/audit-local.json`; web access to that directory is denied by `.htaccess`.

| Existing source | New use |
| --- | --- |
| Home, page 507 | Full custom storefront: announcement, navigation, hero, featured products, categories, XXL proposition, collection highlights, fabric/care detail, new arrivals, campaign, real reviews when available, story, final CTA, footer |
| Shop, page 1488, `/shop-now/` | Dynamic clothing catalog, search, category/size/price filters, sorting, count and pagination |
| About Us, page 529 | Clothing-specific story, Double XL focus, fit/design/quality sections, existing product imagery and verified product descriptions |
| Cart 8 and Checkout 9 | Native WooCommerce cart/checkout forms within the new header/footer; original Elementor documents retained |
| Terms 1407 and Privacy/Refund/Return Policy 1423 | Original legal content and functional footer links |
| Home - 2, page 1951; draft 2028 | Preserved |
| Missing account page (configured ID 867) | Added page 2102, `/my-account/`, using `[woocommerce_my_account]` |
| Existing header menu | Shop and About destinations retained; search, XXL edit, account and cart added to presentation |
| Existing footer 468 | Support email/phone, Instagram and Facebook destinations retained |
| Product category 34, `oversize-tee` | Main clothing merchandising collection |
| Product category 29, Hoodies | Preserved; empty category shown without implying stock |
| Existing product 2045 | Preserved and directly accessible, excluded from default clothing merchandising |

The original Shop setting pointed at missing ID 7, Account at missing ID 867, and Privacy at missing ID 3. Shop now refers to existing page 1488, Account to new page 2102, Privacy to 1423, and Terms to existing page 1407. Previous values and the created page ID are recorded in the `vr_setup_record` option. No existing page bodies were overwritten.

### Product inventory

Twenty variable oversized tees, each priced from the existing WooCommerce data (₹750 at audit), with 140 published variations total. The existing custom `Size` attributes include XS, S, M, L, XL, XXL, and 3XL. They were not converted into global attributes.

| ID | Product |
| --- | --- |
| 1574 | Ninja Oversized Tee |
| 1587 | Kyoto Oversized Tee |
| 1601 | Black Dragon Oversized Tee |
| 1616 | Ancient Warrior Oversized Tee |
| 1630 | Bhushido Oversized Tee |
| 1645 | Tokyo Blue Oversized Tee |
| 1658 | Red Warrior Oversized Tee |
| 1672 | Fox Oversized Tee |
| 1686 | Music Japan Oversized Tee |
| 1700 | Tiger Oversized Tee |
| 1713 | Ronin Oversized Tee |
| 1731 | Samurai Oversized Tee |
| 1746 | Twin Fish Oversized Tee |
| 1760 | Oni Oversized Tee |
| 1774 | The Last Samurai Oversized Tee |
| 1788 | Warrior Spirit Oversized Tee |
| 1802 | Orange Dragon Oversized Tee |
| 1815 | Hokkaido Wave Oversized Tee |
| 1829 | Yellow Dragon Oversized Tee |
| 1843 | Red Octopus Oversized Tee |
| 2045 | Legacy Frido demo product, simple, ₹999; preserved |

All product titles, descriptions, image IDs, prices, sale prices, stock status, attributes, categories, variation IDs and review counts are compared with the original audit by `tools/vyndra/catalog-check.php`.

The clothing descriptions specify 90/10 cotton/polyester, 260 GSM, terry-knit texture, unisex oversized fit, drop shoulders, and care instructions. The fabric sections render these existing descriptions; they do not invent manufacturing claims. Size guides use each product's existing gallery chart. The home and About images reuse media 1902 and 1910; logo 1933 is the original Vyndra artwork. No Frido imagery or branding was imported.

No sales or product reviews were recorded at audit. The would-be bestseller section therefore displays “Collection highlights.” It automatically switches to sales-based merchandising once actual sales exist. Review cards render only approved WooCommerce product reviews; verified-owner badges use WooCommerce's verification API. No working newsletter signup was found, so the final section uses a functional shop CTA and existing social links.

### Existing implementation and plugins

Theme: Hello Elementor. Elementor kit: 430. Original global header/footer templates: 471/468. Original single-product template: 459. ACF, Elementor/Pro, ElementsKit, Jeg Elementor Kit, Site Kit, LiteSpeed Cache, Qikink fulfillment, UpdraftPlus, Advanced Product Size Chart, Advanced Shipment Tracking, Razorpay, Variation Swatches, WooCommerce and Yoast remain active. See the audit JSON for the complete plugin and template inventory.

The legacy `vyndra-product-single-acf` plugin removed WooCommerce's native hooks and repeatedly overwrote product 2045, its attributes and media on `admin_init`. The new layer removes those callbacks, its sample-creation activation hooks, and its demo frontend callbacks. Its ACF field group remains registered. The old plugin file is unchanged.

## Reference interpretation

Reference reviewed: [Frido homepage](https://myfrido.com/).

The reference uses a narrow announcement, clear category navigation, campaign-led first fold, category discovery, product merchandising, offers, trust content, a brand introduction and a broad service footer. This implementation adapts that hierarchy into clothing photography, XXL discovery, measured product details, verified reviews and repeated collection CTAs. It uses static editorial sections rather than an auto-advancing hero, and intentionally omits unverified offers, research claims, delivery promises and testimonials. Mobile reflows the hero, reduces grids to two columns, collapses filters, and replaces desktop navigation with an accessible menu.

## Brand and design system

The actual existing palette is retained from Elementor kit 430:

- Gold `#F9BF29`: custom token `e9e8c9c` (“primary”), also `58a3f5b` used by the old hero.
- Green `#335144`: custom token `d49ac81` (“Button”), also the dark-background token and original logo color.
- Existing system token named “primary” is `#6A6A6A` and is labelled “Basic font,” so it is not treated as the brand accent.

The runtime reads the existing kit colors and uses them as CSS variables. Supporting warm white, gray and state colors are defined at the top of `assets/storefront.css`. Typography uses a system sans-serif stack without new font downloads or licensing requirements. Components include shared header/footer, product card, grid, category tile, price, size chips, filter form, campaign, reviews, breadcrumb and native product controls.

## Editing

Use **Appearance → Customize → Vyndra Storefront** for announcement, hero title/copy/images, logo, category slug, collection banner, brand story, and contact/social destinations. Use `\n` for a title line break. Manage products, prices, stock, images, descriptions, sizes and reviews through their existing WooCommerce screens.

The redesigned Home and About bodies are presentation templates; their original Elementor documents remain in the database. Elementor preview is excluded from the template override. Account/legal page content continues to render normally. Cart and Checkout use native WooCommerce shortcodes because the imported Elementor cart widget emitted missing-setting warnings (`section_coupon_display`). This changes their presentation, not WooCommerce's session, cart, coupon, checkout or payment handlers.

## Commerce and performance

Native WooCommerce gallery, variation form, quantity, add-to-cart, notices, tabs, reviews, cart sessions, coupon checks, checkout, account endpoints and third-party swatches remain in use. No new checkout or payment handler exists. Header cart quantity uses WooCommerce cart fragments.

On the new presentation only, native browser image loading replaces LiteSpeed's JavaScript image placeholders, page optimization is bypassed to preserve dependency order, and the imported Elementor loading overlay is hidden. Elementor's public enqueue method supplies the frontend configuration needed by its add-ons. Plugin configuration values are not changed.

The category filter reads real terms. Size filtering queries existing published, priced, in-stock/backorder variation metadata, including local and global size attributes. It does not rewrite attributes. Catalog visibility rules are respected. Prices and sorting use existing WooCommerce price/sales/rating metadata.

## Validation and remaining acceptance

Evidence and screenshots are in `tools/vyndra/` (not publicly served). PHP files and JavaScript are syntax checked. `browser-check.cjs` covers desktop 1440px, tablet 768px and mobile 390px. `commerce-check.cjs` exercises filters, pagination, search, real variation selection, cart quantities, coupon validation, checkout rendering, account/password-reset routes, mobile navigation/search and cart removal. Scripts use a disposable guest browser session.

Before/after database fingerprints cover product/variation/coupon/order records, categories and relationships, user records, review records, gateway/shipping configuration, active plugins and order tables. WordPress/Elementor may refresh generated asset metadata and session/notice metadata while rendering pages; those are not treated as catalog edits. The separate catalog comparison verifies the actual protected product fields.

**Existing operational blockers:** shipping is enabled for India and requires an address, but no shipping zones or methods are configured, including the default zone. COD and Razorpay are enabled; the missing shipping method prevents completing a physical-product checkout. Account registration and coupons are both disabled by existing settings; these remain unchanged, so signup and coupon-entry forms are intentionally absent. No payment was charged and no test order was created. Live payment, order creation, authenticated customer history, registration and coupon-redemption acceptance cannot be claimed from these local checks. Configure approved shipping and use a payment test environment for the remaining end-to-end checks.

## Rollback

Rename `wp-content/mu-plugins/vyndra-storefront.php` to a non-PHP extension to remove the presentation layer. Keep the folder for later restoration. This also re-enables the legacy product plugin's unsafe seeding callbacks; review that plugin before visiting admin pages after rollback. The page-reference repairs are intentionally independent and keep the formerly broken Shop/Account links working. Their prior values are in `vr_setup_record` if an exact configuration rollback is required; no original page or product needs restoring.

## Recorded results, 27 September 2026

- PHP syntax and frontend JavaScript syntax: pass.
- Browser layout suite: 14 route/viewport checks, no horizontal overflow, no JavaScript errors, no broken images on the inspected main pages.
- Catalog comparison: all 21 products match every audited field, including descriptions, images, prices, stock status, attributes and variation IDs.
- XXL filtering: 20 tees; second page: 8 remaining products; search and empty price range: pass.
- Product 1843: selecting XXL resolves to native variation 1849. Two items add at ₹1,500, and updating to three persists at ₹2,250.
- Native checkout: billing form and both existing gateways render. The 390px checkout has no horizontal overflow. No order submission or payment was performed.
- Missing shipping methods: confirmed in database/configuration and installed WooCommerce source. `WC_Cart::show_shipping()` intentionally returns false when method count is zero, so checkout displays no shipping row even after entering an address.
- Coupons and registration: existing disabled settings preserved.
- Account login form and password-reset route: pass; authenticated login and outgoing reset email were not exercised.
- Mobile menu/Escape, search, collapsible filters and cart removal are covered by the commerce script.

Raw product-meta and user-meta fingerprints differ from the first snapshot after plugin rendering and sessions. The original snapshot contains aggregate hashes rather than a per-row diff, so it cannot attribute every metadata change. The separate catalog comparison passes; product/variation post records, terms, relationships, user records, reviews, commerce settings, shipping tables and all order tables retain their original fingerprints.
