<?php
defined( 'ABSPATH' ) || exit;
$new = vr_products();
$featured = vr_products( array( 'featured' => true ) );
if ( ! $featured ) { $featured = $new; }
?>
<section class="vr-hero">
    <div class="vr-hero-copy"><p class="vr-eyebrow"><span class="vr-dot"></span> THE OVERSIZED STATE OF MIND</p><h1><?php echo vr_lines( vr_setting( 'hero_title' ) ); ?></h1><p class="vr-hero-description"><?php echo esc_html( vr_setting( 'hero_text' ) ); ?></p><div class="vr-hero-buttons"><a class="vr-button" href="<?php echo esc_url( vr_collection_url() ); ?>">Shop T-shirts <?php echo vr_icon( 'arrow' ); ?></a><a class="vr-text-link" href="<?php echo esc_url( vr_xxl_url() ); ?>">Explore XXL ↗</a></div><div class="vr-hero-bottom"><span>EXPRESSIVE GRAPHICS</span><span>RELAXED SILHOUETTES</span></div></div>
    <div class="vr-hero-photo"><?php echo vr_image( vr_setting( 'hero_image' ), 'full', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(max-width: 700px) 100vw, 50vw' ) ); ?><div class="vr-hero-stamp"><span>MORE ROOM</span><strong>XXL</strong><span>MORE YOU</span></div><a class="vr-photo-caption" href="<?php echo esc_url( vr_collection_url() ); ?>"><div><small>THE GRAPHIC CORE COLLECTION</small><strong>Make a little more room.</strong></div><?php echo vr_icon( 'arrow' ); ?></a></div>
</section>
<div class="vr-values-strip"><span><?php echo vr_icon( 'fit' ); ?> An oversized point of view</span><span><?php echo vr_icon( 'check' ); ?> XXL in the collection</span><span><?php echo vr_icon( 'bag' ); ?> Your everyday, reimagined</span></div>
<?php vr_product_section( 'Meet your next everyday tee.', 'IN THE SPOTLIGHT', $featured ); ?>
<section class="vr-container vr-section vr-collections" id="collections">
    <div class="vr-section-heading"><div><p class="vr-eyebrow">FIND YOUR EXPRESSION</p><h2>Your style. Your space.</h2></div><p>Start with a fit. Make it your own.</p></div>
    <div class="vr-category-grid">
        <?php $categories = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'exclude' => array( get_option( 'default_product_cat' ) ) ) ); foreach ( $categories as $category ) : $thumb = get_term_meta( $category->term_id, 'thumbnail_id', true ); if ( ! $thumb && $category->count ) { $sample = wc_get_products( array( 'category' => array( $category->slug ), 'limit' => 1, 'status' => 'publish', 'visibility' => 'catalog' ) ); $thumb = $sample ? $sample[0]->get_image_id() : 0; } ?>
        <a class="vr-category <?php echo $thumb ? '' : 'vr-category-type'; ?>" href="<?php echo esc_url( add_query_arg( 'category', $category->slug, vr_shop_url() ) ); ?>">
            <?php if ( $thumb ) { echo vr_image( $thumb, 'large' ); } else { echo '<span class="vr-category-letter" aria-hidden="true">' . esc_html( mb_substr( $category->name, 0, 1 ) ) . '</span>'; } ?>
            <div><span class="vr-eyebrow"><?php echo $category->count ? esc_html( $category->count . ' styles to explore' ) : 'Explore the category'; ?></span><h3><?php echo esc_html( $category->name ); ?></h3></div><?php echo vr_icon( 'arrow' ); ?>
        </a><?php endforeach; ?>
        <a class="vr-category vr-category-xxl" href="<?php echo esc_url( vr_xxl_url() ); ?>"><span class="vr-category-letter" aria-hidden="true">XXL</span><div><span class="vr-eyebrow">A LITTLE MORE ROOM</span><h3>The Double XL edit</h3></div><?php echo vr_icon( 'arrow' ); ?></a>
    </div>
</section>
<section class="vr-fit-banner"><div class="vr-container vr-fit-inner"><div class="vr-fit-type" aria-hidden="true">XXL<span>IS A STATE OF MIND.</span></div><div><p class="vr-eyebrow">THE DOUBLE XL PERSPECTIVE</p><h2>Made with more<br>room in mind.</h2><p>Good style starts with feeling like yourself. Explore oversized tees in Double XL, and use each product’s size guide to find the proportions that work for you.</p><a class="vr-button vr-button-light" href="<?php echo esc_url( vr_xxl_url() ); ?>">Find your XXL <?php echo vr_icon( 'arrow' ); ?></a></div></div></section>
<?php
$popular = vr_products( array( 'orderby' => 'popularity' ) );
$has_sales = $popular && max( array_map( function ( $p ) { return $p->get_total_sales(); }, $popular ) ) > 0;
vr_product_section( $has_sales ? 'The most-loved lineup.' : 'Graphics with personality.', $has_sales ? 'BESTSELLERS' : 'COLLECTION HIGHLIGHTS', $has_sales ? $popular : vr_products( array( 'offset' => 4 ) ) );
?>
<section class="vr-detail-section vr-container vr-section">
    <div class="vr-detail-photo"><?php echo vr_image( vr_setting( 'detail_image' ), 'large' ); ?><span class="vr-detail-label">A CLOSER LOOK</span></div>
    <div class="vr-detail-copy"><p class="vr-eyebrow">IT’S IN THE DETAILS</p><h2>Know your tee.<br>Love your fit.</h2><?php if ( $new ) : ?><p>Inside the <?php echo esc_html( $new[0]->get_name() ); ?>.</p><div class="vr-product-facts"><?php echo wp_kses_post( wpautop( $new[0]->get_description() ) ); ?></div><a class="vr-text-link" href="<?php echo esc_url( $new[0]->get_permalink() ); ?>">Take a closer look <?php echo vr_icon( 'arrow' ); ?></a><?php endif; ?></div>
</section>
<?php vr_product_section( 'Fresh in your rotation.', 'NEW ARRIVALS', $new, add_query_arg( 'orderby', 'date', vr_collection_url() ) ); ?>
<section class="vr-campaign vr-container"><div><p class="vr-eyebrow">THE GRAPHIC CORE COLLECTION</p><h2><?php echo vr_lines( vr_setting( 'campaign_title' ) ); ?></h2><p><?php echo esc_html( vr_setting( 'campaign_text' ) ); ?></p><a class="vr-button vr-button-dark" href="<?php echo esc_url( vr_collection_url() ); ?>">Explore the collection <?php echo vr_icon( 'arrow' ); ?></a></div><span class="vr-campaign-mark" aria-hidden="true">BE<br>YOU.</span></section>
<?php
$review_ids = wc_get_products( array( 'category' => array( vr_setting( 'collection' ) ), 'status' => 'publish', 'visibility' => 'catalog', 'limit' => -1, 'return' => 'ids' ) );
$reviews = $review_ids ? get_comments( array( 'post__in' => $review_ids, 'status' => 'approve', 'type' => 'review', 'number' => 3 ) ) : array();
if ( $reviews ) : ?>
<section class="vr-container vr-section"><div class="vr-section-heading"><div><p class="vr-eyebrow">IN YOUR WORDS</p><h2>Worn. Loved. Shared.</h2></div></div><div class="vr-review-grid"><?php foreach ( $reviews as $review ) : ?><article class="vr-review"><?php echo wc_get_rating_html( (int) get_comment_meta( $review->comment_ID, 'rating', true ) ); ?><blockquote><?php echo esc_html( wp_strip_all_tags( $review->comment_content ) ); ?></blockquote><strong><?php echo esc_html( $review->comment_author ); ?></strong><?php if ( wc_review_is_from_verified_owner( $review->comment_ID ) ) : ?><small>Verified owner</small><?php endif; ?><a href="<?php echo esc_url( get_permalink( $review->comment_post_ID ) . '#reviews' ); ?>"><?php echo esc_html( get_the_title( $review->comment_post_ID ) ); ?></a></article><?php endforeach; ?></div></section>
<?php endif; ?>
<section class="vr-container vr-story vr-section"><p class="vr-eyebrow">HELLO, WE’RE VYNDRA.</p><h2>A little more expression.<br>A lot more you.</h2><p><?php echo esc_html( vr_setting( 'story' ) ); ?></p><a class="vr-text-link" href="<?php echo esc_url( vr_page_url( 'about-us' ) ); ?>">Our story <?php echo vr_icon( 'arrow' ); ?></a></section>
<section class="vr-final-cta"><div class="vr-container"><div><p class="vr-eyebrow">YOUR NEXT EVERYDAY STARTS HERE</p><h2>Find the tee that feels like you.</h2></div><a class="vr-button" href="<?php echo esc_url( vr_collection_url() ); ?>">Shop the collection <?php echo vr_icon( 'arrow' ); ?></a></div></section>
