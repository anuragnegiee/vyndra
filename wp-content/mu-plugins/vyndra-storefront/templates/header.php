<?php defined( 'ABSPATH' ) || exit; ?>
<div class="vr-announcement"><a href="<?php echo esc_url( vr_collection_url() ); ?>"><?php echo esc_html( vr_setting( 'announcement' ) ); ?> <span aria-hidden="true">↗</span></a></div>
<header class="vr-header">
    <div class="vr-container vr-header-inner">
        <a class="vr-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Vyndra home"><?php echo vr_image( vr_setting( 'logo' ), 'medium', array( 'alt' => 'Vyndra', 'loading' => 'eager' ) ) ?: 'VYNDRA'; ?></a>
        <nav class="vr-desktop-nav" aria-label="Main navigation">
            <a href="<?php echo esc_url( vr_shop_url() ); ?>" <?php if ( vr_catalog_page() ) { echo 'aria-current="page"'; } ?>>Shop all</a>
            <a href="<?php echo esc_url( vr_collection_url() ); ?>">Oversized tees</a>
            <a class="vr-nav-xxl" href="<?php echo esc_url( vr_xxl_url() ); ?>">The XXL edit <span>↗</span></a>
            <a href="<?php echo esc_url( vr_page_url( 'about-us' ) ); ?>" <?php if ( is_page( 'about-us' ) ) { echo 'aria-current="page"'; } ?>>Our story</a>
        </nav>
        <div class="vr-header-actions">
            <button type="button" class="vr-icon-button" data-vr-search aria-label="Search products" aria-expanded="false" aria-controls="vr-search"><?php echo vr_icon( 'search' ); ?></button>
            <a class="vr-icon-button vr-account" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" aria-label="My account"><?php echo vr_icon( 'user' ); ?></a>
            <a class="vr-icon-button vr-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="View cart"><?php echo vr_icon( 'bag' ) . vr_cart_count(); ?></a>
            <button class="vr-icon-button vr-menu-toggle" type="button" data-vr-menu aria-label="Toggle navigation" aria-expanded="false" aria-controls="vr-mobile-nav"><?php echo vr_icon( 'menu' ); ?></button>
        </div>
    </div>
    <form class="vr-header-search vr-container" id="vr-search" action="<?php echo esc_url( vr_shop_url() ); ?>" method="get" hidden>
        <label for="vr-header-query">Find your next tee</label><div><input id="vr-header-query" type="search" name="q" placeholder="Search the collection…"><button class="vr-button" type="submit">Search <?php echo vr_icon( 'search' ); ?></button></div>
    </form>
    <nav class="vr-mobile-nav" id="vr-mobile-nav" aria-label="Mobile navigation" hidden>
        <a href="<?php echo esc_url( vr_shop_url() ); ?>">Shop all <?php echo vr_icon( 'arrow' ); ?></a>
        <a href="<?php echo esc_url( vr_collection_url() ); ?>">Oversized tees <?php echo vr_icon( 'arrow' ); ?></a>
        <a href="<?php echo esc_url( vr_xxl_url() ); ?>">The XXL edit <?php echo vr_icon( 'arrow' ); ?></a>
        <a href="<?php echo esc_url( vr_page_url( 'about-us' ) ); ?>">Our story <?php echo vr_icon( 'arrow' ); ?></a>
        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">My account <?php echo vr_icon( 'user' ); ?></a>
    </nav>
</header>
