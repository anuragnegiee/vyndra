<?php
defined( 'ABSPATH' ) || exit;

// Keep the old ACF field definitions, but stop its destructive seed and demo UI.
add_action( 'plugins_loaded', function () {
    remove_action( 'admin_init', 'vyndra_populate_product_2045_data' );
    remove_action( 'init', 'vyndra_disable_default_wc_tabs', 20 );
    remove_action( 'woocommerce_before_single_product_summary', 'vyndra_render_frido_top_layout', 5 );
    remove_action( 'woocommerce_after_single_product_summary', 'vyndra_render_frido_sections', 5 );
    remove_action( 'wp_enqueue_scripts', 'vyndra_enqueue_product_assets' );
    remove_filter( 'template_include', 'vyndra_override_single_product_template', 999 );
    remove_action( 'activate_vyndra-product-single-acf/vyndra-product-single-acf.php', 'vyndra_create_sample_product_data' );
    remove_action( 'activate_vyndra-product-single-acf/vyndra-product-single-acf.php', 'vyndra_populate_product_2045_data' );
}, 100 );
// Also protect a future reactivation, which includes that plugin after plugins_loaded.
add_action( 'activate_vyndra-product-single-acf/vyndra-product-single-acf.php', function () {
    remove_action( 'activate_vyndra-product-single-acf/vyndra-product-single-acf.php', 'vyndra_create_sample_product_data' );
    remove_action( 'activate_vyndra-product-single-acf/vyndra-product-single-acf.php', 'vyndra_populate_product_2045_data' );
}, -100 );

function vr_setting( $key ) {
    $defaults = array(
        'announcement' => 'ROOM FOR YOU. ROOM TO BE YOU. — Explore the oversized collection',
        'hero_title' => 'Big on comfort.\nBigger on you.',
        'hero_text' => 'T-shirts that fit beyond the standard. Explore everyday oversized style, with Double XL at the heart of it.',
        'hero_image' => 1902,
        'detail_image' => 1910,
        'logo' => 1933,
        'collection' => 'oversize-tee',
        'campaign_title' => 'Your everyday.\nAnything but ordinary.',
        'campaign_text' => 'Find the graphic that feels like you. Explore the Oversized collection.',
        'story' => 'Vyndra brings expressive graphics and everyday streetwear together. Our next chapter puts Double XL in focus: more room to find your fit, and more ways to make it your own.',
        'email' => 'Contact@annieshop.vyndra.in',
        'phone' => '+917973068848',
        'instagram' => 'https://www.instagram.com/vyndrastore/',
        'facebook' => 'https://www.facebook.com/profile.php?id=61587352111308',
    );
    return get_theme_mod( 'vr_' . $key, $defaults[ $key ] ?? '' );
}

add_action( 'customize_register', function ( $c ) {
    $c->add_section( 'vr_storefront', array( 'title' => 'Vyndra Storefront', 'priority' => 30 ) );
    $fields = array( 'announcement' => 'Announcement', 'hero_title' => 'Hero title (use \\n for a line break)', 'hero_text' => 'Hero introduction', 'campaign_title' => 'Collection banner title', 'campaign_text' => 'Collection banner text', 'story' => 'Brand story', 'collection' => 'Merchandising category slug', 'email' => 'Support email', 'phone' => 'Support phone', 'instagram' => 'Instagram URL', 'facebook' => 'Facebook URL' );
    foreach ( $fields as $key => $label ) {
        $sanitize = in_array( $key, array( 'instagram', 'facebook' ), true ) ? 'esc_url_raw' : ( 'email' === $key ? 'sanitize_email' : 'sanitize_textarea_field' );
        $c->add_setting( 'vr_' . $key, array( 'default' => vr_setting( $key ), 'sanitize_callback' => $sanitize ) );
        $c->add_control( 'vr_' . $key, array( 'label' => $label, 'section' => 'vr_storefront', 'type' => 'textarea' ) );
    }
    foreach ( array( 'hero_image' => 'Hero photograph', 'detail_image' => 'Collection detail photograph', 'logo' => 'Brand logo' ) as $key => $label ) {
        $c->add_setting( 'vr_' . $key, array( 'default' => vr_setting( $key ), 'sanitize_callback' => 'absint' ) );
        $c->add_control( new WP_Customize_Media_Control( $c, 'vr_' . $key, array( 'label' => $label, 'section' => 'vr_storefront', 'mime_type' => 'image' ) ) );
    }
} );

function vr_shop_url() { return get_permalink( wc_get_page_id( 'shop' ) ) ?: home_url( '/shop-now/' ); }
function vr_page_url( $slug ) { $p = get_page_by_path( $slug ); return $p && 'publish' === $p->post_status ? get_permalink( $p ) : ''; }
function vr_catalog_page() { return is_shop() || is_product_taxonomy() || is_page( 'shop-now' ) || ( is_search() && 'product' === get_query_var( 'post_type' ) ); }
function vr_enabled() {
    if ( ! function_exists( 'WC' ) || is_admin() || is_feed() || wp_is_json_request() ) { return false; }
    if ( isset( $_GET['elementor-preview'] ) ) { return false; }
    return is_front_page() || vr_catalog_page() || is_product() || is_page( array( 'about-us', 'privacy-refund-return-policy', 'terms-conditions' ) ) || is_cart() || is_checkout() || is_account_page();
}

add_filter( 'template_include', function ( $template ) {
    return vr_enabled() ? __DIR__ . '/templates/shell.php' : $template;
}, 10000 );
add_filter( 'body_class', function ( $classes ) { if ( vr_enabled() ) { $classes[] = 'vr-store'; } return $classes; } );

// The imported kit's delayed scripts can strand its full-screen transition overlay.
// Keep native browser image loading and script order on this presentation only.
add_action( 'wp', function () {
    if ( vr_enabled() && ! defined( 'LITESPEED_NO_PAGEOPTM' ) ) { define( 'LITESPEED_NO_PAGEOPTM', true ); }
    if ( vr_enabled() ) { remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); }
} );
add_filter( 'wp_get_attachment_image_attributes', function ( $attrs ) {
    if ( vr_enabled() ) { $attrs['class'] = ( $attrs['class'] ?? '' ) . ' skip-lazy'; }
    return $attrs;
} );

add_action( 'after_setup_theme', function () {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}, 30 );

add_action( 'wp_enqueue_scripts', function () {
    if ( ! vr_enabled() ) { return; }
    // Elementor add-ons enqueue frontend.js even when the page body is our PHP template.
    // Its public enqueue method supplies the matching runtime configuration.
    if ( class_exists( '\Elementor\Plugin' ) ) { \Elementor\Plugin::$instance->frontend->enqueue_scripts(); }
    $base = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'vr-storefront', $base . 'assets/storefront.css', array(), filemtime( __DIR__ . '/assets/storefront.css' ) );
    // These are the existing Elementor kit tokens, not colors from the reference.
    $kit = get_post_meta( get_option( 'elementor_active_kit' ), '_elementor_page_settings', true );
    $colors = array_column( $kit['custom_colors'] ?? array(), 'color', '_id' );
    $gold = sanitize_hex_color( $colors['e9e8c9c'] ?? '#F9BF29' ) ?: '#F9BF29';
    $green = sanitize_hex_color( $colors['d49ac81'] ?? '#335144' ) ?: '#335144';
    wp_add_inline_style( 'vr-storefront', 'body.vr-store{--vr-primary:' . $gold . ';--vr-brand:' . $green . ';}' );
    wp_enqueue_script( 'vr-storefront', $base . 'assets/storefront.js', array( 'jquery', 'wc-add-to-cart', 'wc-cart-fragments' ), filemtime( __DIR__ . '/assets/storefront.js' ), true );
}, 1000 );

add_filter( 'wpseo_title', function ( $title ) {
    if ( ! vr_enabled() ) { return $title; }
    if ( is_front_page() ) { return 'Vyndra — Oversized T-shirts & the XXL Edit'; }
    if ( vr_catalog_page() ) { return 'Shop Oversized T-shirts | Vyndra'; }
    return wp_strip_all_tags( get_the_title( get_queried_object_id() ) ) . ' | Vyndra';
} );

function vr_icon( $name ) {
    $paths = array(
        'search' => '<circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 4 4"/>',
        'user' => '<circle cx="12" cy="8" r="3.5"/><path d="M5 21v-2a7 7 0 0 1 14 0v2"/>',
        'bag' => '<path d="M5 7h14l1 14H4L5 7Z"/><path d="M8 8V6a4 4 0 0 1 8 0v2"/>',
        'arrow' => '<path d="M4 12h16m-6-6 6 6-6 6"/>',
        'menu' => '<path d="M3 6h18M3 12h18M3 18h18"/>',
        'fit' => '<path d="m8 3 4 2 4-2 6 5-4 4-2-2v11H8V10l-2 2-4-4 6-5Z"/>',
        'check' => '<path d="m5 12 4 4L19 6"/>',
    );
    return '<svg class="vr-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ( $paths[ $name ] ?? $paths['arrow'] ) . '</svg>';
}
function vr_lines( $text ) { return nl2br( esc_html( str_replace( '\\n', "\n", $text ) ) ); }
function vr_image( $id, $size = 'large', $attrs = array() ) { return wp_get_attachment_image( absint( $id ), $size, false, $attrs ); }
function vr_collection_url() { return add_query_arg( 'category', vr_setting( 'collection' ), vr_shop_url() ); }
function vr_xxl_url() { return add_query_arg( 'size', 'XXL', vr_collection_url() ); }
function vr_cart_count() { return '<span class="vr-cart-count">' . esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ) . '</span>'; }
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) { $fragments['.vr-cart-count'] = vr_cart_count(); return $fragments; } );
add_filter( 'woocommerce_gateway_icon', function ( $icon, $gateway ) {
    // Razorpay's remote badge is unavailable locally; its configured text label remains.
    return vr_enabled() && 'razorpay' === $gateway ? '' : $icon;
}, 10, 2 );

function vr_products( $args = array() ) {
    if ( isset( $args['orderby'] ) && 'popularity' === $args['orderby'] ) {
        $args['vr_orderby_sales'] = true;
        $args['orderby'] = 'date';
    }
    return wc_get_products( array_merge( array( 'status' => 'publish', 'visibility' => 'catalog', 'category' => array( vr_setting( 'collection' ) ), 'limit' => 4, 'orderby' => 'date', 'order' => 'DESC' ), $args ) );
}
add_filter( 'woocommerce_product_data_store_cpt_get_products_query', function ( $query, $vars ) {
    if ( ! empty( $vars['vr_orderby_sales'] ) ) {
        $query['meta_key'] = 'total_sales';
        $query['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
    }
    return $query;
}, 10, 2 );
function vr_sizes( $product ) {
    $sizes = array();
    foreach ( $product->get_attributes() as $attribute ) {
        if ( ! preg_match( '/size/i', $attribute->get_name() ) ) { continue; }
        $sizes = $attribute->is_taxonomy() ? wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) ) : $attribute->get_options();
    }
    $order = array_flip( array( 'XS', 'S', 'M', 'L', 'XL', 'XXL', '2XL', '3XL', '4XL', '5XL' ) );
    usort( $sizes, function ( $a, $b ) use ( $order ) { return ( $order[ strtoupper( $a ) ] ?? 99 ) <=> ( $order[ strtoupper( $b ) ] ?? 99 ); } );
    return $sizes;
}
function vr_card( $item ) {
    global $product;
    $previous = $product;
    $product = $item;
    $sizes = vr_sizes( $item );
    ?>
    <li <?php wc_product_class( 'vr-card', $item ); ?>>
        <a class="vr-card-photo" href="<?php echo esc_url( $item->get_permalink() ); ?>" aria-label="<?php echo esc_attr( $item->get_name() ); ?>">
            <?php echo $item->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) ); ?>
            <?php if ( $item->is_on_sale() ) : ?><span class="vr-tag">Sale</span><?php elseif ( in_array( 'XXL', $sizes, true ) || in_array( '2XL', $sizes, true ) ) : ?><span class="vr-tag">XXL available</span><?php endif; ?>
            <span class="vr-card-arrow"><?php echo vr_icon( 'arrow' ); ?></span>
        </a>
        <div class="vr-card-body">
            <span class="vr-eyebrow"><?php echo esc_html( implode( ' / ', wp_get_post_terms( $item->get_id(), 'product_cat', array( 'fields' => 'names' ) ) ) ); ?></span>
            <h3><a href="<?php echo esc_url( $item->get_permalink() ); ?>"><?php echo esc_html( $item->get_name() ); ?></a></h3>
            <?php if ( $item->get_rating_count() ) { echo wc_get_rating_html( $item->get_average_rating(), $item->get_rating_count() ); } ?>
            <div class="vr-card-price"><?php echo wp_kses_post( $item->get_price_html() ); ?></div>
            <?php if ( $sizes ) : ?><p class="vr-sizes" aria-label="Sizes"><?php foreach ( $sizes as $size ) : ?><span class="<?php echo in_array( strtoupper( $size ), array( 'XXL', '2XL' ), true ) ? 'is-xxl' : ''; ?>"><?php echo esc_html( $size ); ?></span><?php endforeach; ?></p><?php endif; ?>
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
    </li>
    <?php
    $product = $previous;
}
function vr_product_section( $title, $eyebrow, $items, $url = '' ) {
    if ( ! $items ) { return; }
    echo '<section class="vr-section vr-container"><div class="vr-section-heading"><div><p class="vr-eyebrow">' . esc_html( $eyebrow ) . '</p><h2>' . esc_html( $title ) . '</h2></div><a class="vr-text-link" href="' . esc_url( $url ?: vr_collection_url() ) . '">Shop the collection ' . vr_icon( 'arrow' ) . '</a></div><ul class="vr-grid products">';
    foreach ( $items as $item ) { vr_card( $item ); }
    echo '</ul></section>';
}

function vr_input( $key ) { return isset( $_GET[ $key ] ) && is_scalar( $_GET[ $key ] ) ? sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) : ''; }

/** Filter local and global variation attributes without converting existing catalog data. */
function vr_size_product_ids( $size ) {
    global $wpdb;
    $keys = array( 'attribute_size' );
    foreach ( wc_get_attribute_taxonomies() as $attribute ) {
        if ( false !== stripos( $attribute->attribute_name, 'size' ) ) { $keys[] = 'attribute_pa_' . $attribute->attribute_name; }
    }
    $placeholders = implode( ',', array_fill( 0, count( $keys ), '%s' ) );
    $values = array_merge( $keys, array( $size, sanitize_title( $size ) ) );
    $sql = "SELECT DISTINCT p.post_parent FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} a ON a.post_id=p.ID WHERE p.post_type='product_variation' AND p.post_status='publish' AND a.meta_key IN ($placeholders) AND (a.meta_value IN (%s,%s) OR a.meta_value='') AND EXISTS (SELECT 1 FROM {$wpdb->postmeta} price WHERE price.post_id=p.ID AND price.meta_key='_price' AND price.meta_value<>'') AND NOT EXISTS (SELECT 1 FROM {$wpdb->postmeta} stock WHERE stock.post_id=p.ID AND stock.meta_key='_stock_status' AND stock.meta_value='outofstock')";
    return array_map( 'absint', $wpdb->get_col( $wpdb->prepare( $sql, $values ) ) );
}

function vr_catalog_query() {
    $args = array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 12, 'paged' => max( 1, absint( vr_input( 'pg' ) ) ), 's' => vr_input( 'q' ) ?: get_search_query(), 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
    $tax = array( 'relation' => 'AND' );
    $visibility = wc_get_product_visibility_term_ids();
    $excluded = array( $visibility[ is_search() || $args['s'] ? 'exclude-from-search' : 'exclude-from-catalog' ] );
    if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) { $excluded[] = $visibility['outofstock']; }
    $tax[] = array( 'taxonomy' => 'product_visibility', 'field' => 'term_taxonomy_id', 'terms' => $excluded, 'operator' => 'NOT IN' );
    if ( is_product_taxonomy() ) { $term = get_queried_object(); $tax[] = array( 'taxonomy' => $term->taxonomy, 'field' => 'term_id', 'terms' => $term->term_id ); }
    if ( vr_input( 'category' ) ) { $tax[] = array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => vr_input( 'category' ) ); }
    elseif ( ! is_product_taxonomy() ) {
        // The legacy demo stays intact at its product URL, outside clothing merchandising.
        $clothing = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'exclude' => array( get_option( 'default_product_cat' ) ), 'fields' => 'ids' ) );
        if ( ! is_wp_error( $clothing ) && $clothing ) { $tax[] = array( 'taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $clothing ); }
    }
    $args['tax_query'] = $tax;
    if ( vr_input( 'size' ) ) { $args['post__in'] = vr_size_product_ids( vr_input( 'size' ) ) ?: array( 0 ); }
    $meta = array( 'relation' => 'AND' );
    foreach ( array( 'min_price' => '>=', 'max_price' => '<=' ) as $key => $compare ) {
        if ( '' !== vr_input( $key ) && is_numeric( vr_input( $key ) ) ) { $meta[] = array( 'key' => '_price', 'value' => max( 0, (float) vr_input( $key ) ), 'compare' => $compare, 'type' => 'DECIMAL(20,4)' ); }
    }
    if ( count( $meta ) > 1 ) { $args['meta_query'] = $meta; }
    switch ( vr_input( 'orderby' ) ) {
        case 'date': $args['orderby'] = 'date'; $args['order'] = 'DESC'; break;
        case 'price': case 'price-desc': $args['meta_key'] = '_price'; $args['orderby'] = 'meta_value_num'; $args['order'] = 'price' === vr_input( 'orderby' ) ? 'ASC' : 'DESC'; break;
        case 'popularity': $args['meta_key'] = 'total_sales'; $args['orderby'] = 'meta_value_num'; $args['order'] = 'DESC'; break;
        case 'rating': $args['meta_key'] = '_wc_average_rating'; $args['orderby'] = 'meta_value_num'; $args['order'] = 'DESC'; break;
    }
    return new WP_Query( $args );
}

function vr_size_chart_id( $product ) {
    foreach ( $product->get_gallery_image_ids() as $id ) {
        if ( preg_match( '/size.?chart|size.?guide/i', get_the_title( $id ) ) ) { return $id; }
    }
    return 0;
}
add_action( 'woocommerce_single_product_summary', function () {
    if ( ! vr_enabled() ) { return; }
    global $product;
    $sizes = vr_sizes( $product );
    if ( $sizes ) { echo '<p class="vr-fit-note">' . vr_icon( 'fit' ) . ' Find your fit · ' . esc_html( implode( ' / ', $sizes ) ) . '</p>'; }
    $chart = vr_size_chart_id( $product );
    if ( $chart ) {
        echo '<details class="vr-size-guide"><summary>Size guide <span>↗</span></summary><div>' . vr_image( $chart, 'large', array( 'alt' => $product->get_name() . ' size chart' ) ) . '</div></details>';
    }
}, 25 );
add_action( 'woocommerce_single_product_summary', function () {
    if ( ! vr_enabled() ) { return; }
    echo '<div class="vr-delivery-note"><p>' . vr_icon( 'check' ) . ' Delivery options are calculated at checkout.</p><a href="' . esc_url( vr_page_url( 'privacy-refund-return-policy' ) ) . '">Read shipping, returns &amp; refund information ' . vr_icon( 'arrow' ) . '</a></div>';
}, 35 );

add_filter( 'woocommerce_output_related_products_args', function ( $args ) { if ( vr_enabled() ) { $args['posts_per_page'] = 4; $args['columns'] = 4; } return $args; } );

// Use our reusable card only within our own presentation, retaining native loop/cart actions.
add_filter( 'wc_get_template_part', function ( $template, $slug, $name ) {
    if ( vr_enabled() && 'content' === $slug && 'product' === $name ) { return __DIR__ . '/templates/card.php'; }
    return $template;
}, 10, 3 );
