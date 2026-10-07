<?php
defined( 'ABSPATH' ) || exit;
$catalog = vr_catalog_query();
$categories = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'exclude' => array( get_option( 'default_product_cat' ) ) ) );
$size_options = array();
foreach ( wc_get_products( array( 'limit' => -1, 'status' => 'publish', 'visibility' => 'catalog' ) ) as $item ) { $size_options = array_unique( array_merge( $size_options, vr_sizes( $item ) ) ); }
$size_order = array_flip( array( 'XS', 'S', 'M', 'L', 'XL', 'XXL', '2XL', '3XL', '4XL' ) );
usort( $size_options, function ( $a, $b ) use ( $size_order ) { return ( $size_order[ $a ] ?? 99 ) <=> ( $size_order[ $b ] ?? 99 ); } );
$filter_action = is_product_taxonomy() ? get_term_link( get_queried_object() ) : vr_shop_url();
?>
<section class="vr-shop-intro"><div class="vr-container"><div><p class="vr-eyebrow">THE VYNDRA WARDROBE</p><h1><?php echo is_product_taxonomy() ? esc_html( single_term_title( '', false ) ) : 'Find your everyday.'; ?></h1><p>Expressive graphics. Room to move. A fit that’s yours.</p></div><a class="vr-shop-xxl" href="<?php echo esc_url( vr_xxl_url() ); ?>"><strong>XXL</strong><span>Explore the edit ↗</span></a></div></section>
<div class="vr-container vr-shop-layout">
    <aside class="vr-filters"><details open><summary>Filter the collection <span>＋</span></summary>
        <form method="get" action="<?php echo esc_url( $filter_action ); ?>">
            <label for="vr-q">Search</label><input type="search" id="vr-q" name="q" value="<?php echo esc_attr( vr_input( 'q' ) ?: get_search_query() ); ?>" placeholder="Find a tee…">
            <label for="vr-category">Category</label><select id="vr-category" name="category"><option value="">All categories</option><?php foreach ( $categories as $cat ) : ?><option value="<?php echo esc_attr( $cat->slug ); ?>" <?php selected( vr_input( 'category' ), $cat->slug ); ?>><?php echo esc_html( $cat->name . ' (' . $cat->count . ')' ); ?></option><?php endforeach; ?></select>
            <fieldset><legend>Size</legend><div class="vr-size-filters"><label><input type="radio" name="size" value="" <?php checked( vr_input( 'size' ), '' ); ?>><span>All</span></label><?php foreach ( $size_options as $size ) : ?><label><input type="radio" name="size" value="<?php echo esc_attr( $size ); ?>" <?php checked( vr_input( 'size' ), $size ); ?>><span><?php echo esc_html( $size ); ?></span></label><?php endforeach; ?></div></fieldset>
            <fieldset><legend>Price (<?php echo esc_html( get_woocommerce_currency_symbol() ); ?>)</legend><div class="vr-price-filter"><label class="screen-reader-text" for="vr-min">Minimum price</label><input id="vr-min" name="min_price" type="number" min="0" step="any" placeholder="Min" value="<?php echo esc_attr( vr_input( 'min_price' ) ); ?>"><span>–</span><label class="screen-reader-text" for="vr-max">Maximum price</label><input id="vr-max" name="max_price" type="number" min="0" step="any" placeholder="Max" value="<?php echo esc_attr( vr_input( 'max_price' ) ); ?>"></div></fieldset>
            <label for="vr-sort">Sort by</label><select id="vr-sort" name="orderby"><?php foreach ( array( '' => 'Recommended', 'date' => 'Newest arrivals', 'price' => 'Price: low to high', 'price-desc' => 'Price: high to low', 'popularity' => 'Popularity', 'rating' => 'Customer rating' ) as $value => $label ) : ?><option value="<?php echo esc_attr( $value ); ?>" <?php selected( vr_input( 'orderby' ), $value ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select>
            <button type="submit" class="vr-button">Apply filters <?php echo vr_icon( 'arrow' ); ?></button><a class="vr-reset" href="<?php echo esc_url( $filter_action ); ?>">Clear filters</a>
        </form>
    </details></aside>
    <div class="vr-shop-results woocommerce">
        <?php woocommerce_output_all_notices(); ?>
        <div class="vr-results-heading"><p role="status"><?php echo esc_html( $catalog->found_posts ); ?> <?php echo 1 === (int) $catalog->found_posts ? 'product' : 'products'; ?><?php if ( vr_input( 'size' ) ) { echo ' · Size ' . esc_html( vr_input( 'size' ) ); } ?></p><span>Make room for your next favourite.</span></div>
        <?php if ( $catalog->have_posts() ) : ?><ul class="products vr-grid vr-shop-grid"><?php while ( $catalog->have_posts() ) { $catalog->the_post(); $item = wc_get_product( get_the_ID() ); if ( $item ) { vr_card( $item ); } } ?></ul>
        <nav class="vr-pagination" aria-label="Product pages"><?php echo paginate_links( array( 'base' => esc_url_raw( add_query_arg( 'pg', '%#%' ) ), 'format' => '', 'current' => max( 1, absint( vr_input( 'pg' ) ) ), 'total' => $catalog->max_num_pages, 'prev_text' => '← Previous', 'next_text' => 'Next →', 'type' => 'list' ) ); ?></nav>
        <?php else : ?><div class="vr-empty"><h2>No matches this time.</h2><p>Try another size, category, or price range.</p><a class="vr-button" href="<?php echo esc_url( vr_shop_url() ); ?>">Explore all products <?php echo vr_icon( 'arrow' ); ?></a></div><?php endif; wp_reset_postdata(); ?>
    </div>
</div>
