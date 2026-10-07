<?php defined( 'ABSPATH' ) || exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="<?php bloginfo( 'charset' ); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="vr-skip" href="#main">Skip to content</a>
<?php require __DIR__ . '/header.php'; ?>
<main id="main" tabindex="-1">
<?php
if ( is_front_page() ) { require __DIR__ . '/home.php'; }
elseif ( vr_catalog_page() ) { require __DIR__ . '/shop.php'; }
elseif ( is_product() ) {
    echo '<div class="vr-container vr-single woocommerce">';
    woocommerce_breadcrumb();
    while ( have_posts() ) { the_post(); wc_get_template_part( 'content', 'single-product' ); }
    echo '</div>';
} elseif ( is_page( 'about-us' ) ) { require __DIR__ . '/about.php'; }
else {
    echo '<div class="vr-container vr-section vr-utility">';
    while ( have_posts() ) {
        the_post();
        echo '<h1>' . esc_html( get_the_title() ) . '</h1>';
        // Retain WooCommerce's exact cart/checkout handlers without imported widget setting errors.
        if ( is_cart() ) { echo do_shortcode( '[woocommerce_cart]' ); }
        elseif ( is_checkout() ) { echo do_shortcode( '[woocommerce_checkout]' ); }
        else { the_content(); }
    }
    echo '</div>';
}
?>
</main>
<?php require __DIR__ . '/footer.php'; wp_footer(); ?>
</body></html>
