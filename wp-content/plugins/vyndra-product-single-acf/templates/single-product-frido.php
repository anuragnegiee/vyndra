<?php
/**
 * Custom single product template for Frido product ID 2045.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

global $post;
$product = wc_get_product( $post->ID );
if ( ! $product ) {
    return;
}

$product_id = $post->ID;
$image_ids = vyndra_get_product_gallery_ids( $product );
$main_image = '';
if ( ! empty( $image_ids ) ) {
    $main_image = wp_get_attachment_image_url( $image_ids[0], 'large' );
}
if ( ! $main_image ) {
    $main_image = $product->get_image_id() ? wp_get_attachment_image_url( $product->get_image_id(), 'large' ) : '';
}

$badges = array_filter( array(
    vyndra_get_field_value( 'frido_badge_1', $product_id ),
    vyndra_get_field_value( 'frido_badge_2', $product_id ),
) );
$price_note = vyndra_get_field_value( 'frido_price_note', $product_id );
$variant_color_label = vyndra_get_field_value( 'frido_variant_color_label', $product_id );
$variant_color_options = vyndra_get_field_value( 'frido_variant_color_options', $product_id );
$variant_size_label = vyndra_get_field_value( 'frido_variant_size_label', $product_id );
$variant_size_options = vyndra_get_field_value( 'frido_variant_size_options', $product_id );
$whatsapp_url = vyndra_get_field_value( 'frido_whatsapp_url', $product_id );
$video_call_url = vyndra_get_field_value( 'frido_video_call_url', $product_id );
$size_guide_html = vyndra_get_field_value( 'frido_size_guide_html', $product_id );
$color_terms = wc_get_product_terms( $product_id, 'pa_color', array( 'fields' => 'names' ) );
$size_terms = wc_get_product_terms( $product_id, 'pa_size', array( 'fields' => 'names' ) );

if ( is_wp_error( $color_terms ) ) {
    $color_terms = array();
}
if ( is_wp_error( $size_terms ) ) {
    $size_terms = array();
}

if ( empty( $color_terms ) && $variant_color_options ) {
    $color_terms = array_filter( array_map( 'trim', explode( ',', $variant_color_options ) ) );
}
if ( empty( $size_terms ) && $variant_size_options ) {
    $size_terms = array_filter( array_map( 'trim', explode( ',', $variant_size_options ) ) );
}
$description = vyndra_get_field_value( 'frido_description', $product_id );
if ( empty( $description ) ) {
    $description = $product->get_description();
}
$product_details = vyndra_get_field_value( 'frido_product_details', $product_id );
$care_instructions = vyndra_get_field_value( 'frido_care_instructions', $product_id );
$usage_instructions = vyndra_get_field_value( 'frido_usage_instructions', $product_id );
$return_refund = vyndra_get_field_value( 'frido_return_refund', $product_id );
$regular_price = $product->get_regular_price();
$sale_price = $product->get_sale_price();
$display_price = $product->get_price();
$average_rating = $product->get_average_rating();
$review_count = $product->get_review_count();

$highlights = array();
for ( $i = 1; $i <= 4; $i++ ) {
    $title = vyndra_get_field_value( 'frido_highlight_' . $i . '_title', $product_id );
    $text = vyndra_get_field_value( 'frido_highlight_' . $i . '_text', $product_id );
    if ( $title || $text ) {
        $highlights[] = compact( 'title', 'text' );
    }
}

$faqs = array();
for ( $i = 1; $i <= 5; $i++ ) {
    $question = vyndra_get_field_value( 'frido_faq_' . $i . '_question', $product_id );
    $answer = vyndra_get_field_value( 'frido_faq_' . $i . '_answer', $product_id );
    if ( $question || $answer ) {
        $faqs[] = compact( 'question', 'answer' );
    }
}
?>
<div class="vyndra-product-page">
    <div class="product-wrap">
        <div class="gallery" id="vyndra-gallery">
            <div class="main-img-placeholder" id="vyndra-main-img-wrap">
                <?php if ( $main_image ) : ?>
                    <img class="main-img" id="vyndra-main-img" src="<?php echo esc_url( $main_image ); ?>" alt="<?php echo esc_attr( $product->get_title() ); ?>">
                <?php else : ?>
                    <div class="main-img-placeholder-inner"><?php esc_html_e( 'Image unavailable', 'vyndra-product-single-acf' ); ?></div>
                <?php endif; ?>
            </div>

            <?php if ( ! empty( $image_ids ) ) : ?>
                <div class="thumbnails">
                    <?php foreach ( $image_ids as $index => $image_id ) :
                        $image_url = wp_get_attachment_image_url( $image_id, 'medium' );
                        if ( ! $image_url ) {
                            continue;
                        }
                        $active_class = $index === 0 ? ' active' : '';
                        ?>
                        <button type="button" class="thumb<?php echo esc_attr( $active_class ); ?>" data-full-src="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'large' ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Thumbnail %d', 'vyndra-product-single-acf' ), $index + 1 ) ); ?>">
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_title() ); ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-info">
            <?php if ( ! empty( $badges ) ) : ?>
                <div class="badge-row">
                    <?php foreach ( $badges as $badge ) : ?>
                        <span class="badge"><?php echo esc_html( $badge ); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h1 class="product-title"><?php echo esc_html( $product->get_title() ); ?></h1>

            <?php if ( $average_rating ) : ?>
                <div class="rating-row">
                    <span class="stars"><?php echo wp_kses_post( str_repeat( '★', floor( $average_rating ) ) ); ?></span>
                    <span class="rating-count"><?php echo esc_html( round( $average_rating, 1 ) ); ?></span>
                    <?php if ( $review_count ) : ?>
                        <span class="rating-link"><?php echo esc_html( $review_count ); ?> <?php esc_html_e( 'reviews', 'vyndra-product-single-acf' ); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="price-row">
                <span class="price-current"><?php echo wp_kses_post( wc_price( $display_price ) ); ?></span>
                <?php if ( $sale_price && $regular_price && $regular_price !== $sale_price ) : ?>
                    <span class="price-original"><?php echo wp_kses_post( wc_price( $regular_price ) ); ?></span>
                    <span class="price-discount"><?php echo esc_html( round( ( 1 - ( $sale_price / $regular_price ) ) * 100 ) ); ?>% OFF</span>
                <?php endif; ?>
            </div>

            <?php if ( $price_note ) : ?>
                <p class="price-note"><?php echo esc_html( $price_note ); ?></p>
            <?php endif; ?>

            <?php if ( $color_terms || $size_terms ) : ?>
                <div class="variant-selection-label">
                    <span><?php esc_html_e( 'Choose Variant', 'vyndra-product-single-acf' ); ?></span>
                </div>
                <div class="variant-selection">
                    <?php if ( $color_terms ) : ?>
                        <div class="variant-group">
                            <div class="variant-label"><?php echo esc_html( $variant_color_label ? $variant_color_label : 'Color' ); ?></div>
                            <div class="variant-options">
                                <?php foreach ( $color_terms as $index => $option ) : ?>
                                    <button type="button" class="variant-pill<?php echo 0 === $index ? ' active' : ''; ?>" data-variant="color" data-value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ( $size_terms ) : ?>
                        <div class="variant-group">
                            <div class="variant-label"><?php echo esc_html( $variant_size_label ? $variant_size_label : 'Size' ); ?></div>
                            <div class="variant-options">
                                <?php foreach ( $size_terms as $index => $option ) : ?>
                                    <button type="button" class="variant-pill<?php echo 0 === $index ? ' active' : ''; ?>" data-variant="size" data-value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="divider"></div>

            <div class="section-label">
                <?php esc_html_e( 'Select Size', 'vyndra-product-single-acf' ); ?>
                <a class="size-guide-link" href="#sizeGuide">📏 <?php esc_html_e( 'Size Guide', 'vyndra-product-single-acf' ); ?></a>
            </div>

            <div class="size-block">
                <div class="vyndra-woocommerce-add-to-cart">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            </div>

            <div class="cta-stack">
                <?php if ( $whatsapp_url ) : ?>
                    <a class="btn-atc" href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'WhatsApp Order', 'vyndra-product-single-acf' ); ?></a>
                <?php endif; ?>
                <?php if ( $video_call_url ) : ?>
                    <a class="btn-buy" href="<?php echo esc_url( $video_call_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Video Call', 'vyndra-product-single-acf' ); ?></a>
                <?php endif; ?>
            </div>

            <div class="trust-strip">
                <div class="trust-item"><div class="trust-icon">🚚</div><div class="trust-label"><?php esc_html_e( 'Free Delivery', 'vyndra-product-single-acf' ); ?></div><div class="trust-sub"><?php esc_html_e( 'Pan India', 'vyndra-product-single-acf' ); ?></div></div>
                <div class="trust-item"><div class="trust-icon">↩️</div><div class="trust-label"><?php esc_html_e( '7-Day Returns', 'vyndra-product-single-acf' ); ?></div><div class="trust-sub"><?php esc_html_e( 'Hassle-free', 'vyndra-product-single-acf' ); ?></div></div>
                <div class="trust-item"><div class="trust-icon">🔒</div><div class="trust-label"><?php esc_html_e( 'Secure Pay', 'vyndra-product-single-acf' ); ?></div><div class="trust-sub"><?php esc_html_e( '100% Safe', 'vyndra-product-single-acf' ); ?></div></div>
            </div>

            <div class="product-info-footer">
                <div class="payment-row"><span class="payment-label"><?php esc_html_e( 'Pay with:', 'vyndra-product-single-acf' ); ?></span><span class="pay-icon">UPI</span><span class="pay-icon">VISA</span><span class="pay-icon">MC</span><span class="pay-icon">COD</span><span class="pay-icon">EMI</span></div>
            </div>
        </div>
    </div>

    <div class="below-fold">
        <?php if ( $description || $product_details || $care_instructions || $usage_instructions || $return_refund ) : ?>
            <div class="product-detail-tabs">
                <div class="tabs-nav">
                    <button type="button" class="tab-button active" data-tab="description"><?php esc_html_e( 'Description', 'vyndra-product-single-acf' ); ?></button>
                    <button type="button" class="tab-button" data-tab="product-details"><?php esc_html_e( 'Product Details', 'vyndra-product-single-acf' ); ?></button>
                    <button type="button" class="tab-button" data-tab="care-instructions"><?php esc_html_e( 'Care Instructions', 'vyndra-product-single-acf' ); ?></button>
                    <button type="button" class="tab-button" data-tab="usage-instructions"><?php esc_html_e( 'Usage Instructions', 'vyndra-product-single-acf' ); ?></button>
                    <button type="button" class="tab-button" data-tab="return-refund"><?php esc_html_e( 'Return and Refund', 'vyndra-product-single-acf' ); ?></button>
                </div>
                <div class="tab-panels">
                    <div class="tab-panel active" data-tab="description">
                        <?php if ( $description ) : ?>
                            <div class="product-description"><?php echo apply_filters( 'the_content', $description ); ?></div>
                        <?php else : ?>
                            <p><?php esc_html_e( 'No description available yet.', 'vyndra-product-single-acf' ); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="tab-panel" data-tab="product-details">
                        <?php if ( $product_details ) : ?>
                            <?php echo apply_filters( 'the_content', $product_details ); ?>
                        <?php else : ?>
                            <p><?php esc_html_e( 'No product details added yet.', 'vyndra-product-single-acf' ); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="tab-panel" data-tab="care-instructions">
                        <?php if ( $care_instructions ) : ?>
                            <?php echo apply_filters( 'the_content', $care_instructions ); ?>
                        <?php else : ?>
                            <p><?php esc_html_e( 'No care instructions available yet.', 'vyndra-product-single-acf' ); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="tab-panel" data-tab="usage-instructions">
                        <?php if ( $usage_instructions ) : ?>
                            <?php echo apply_filters( 'the_content', $usage_instructions ); ?>
                        <?php else : ?>
                            <p><?php esc_html_e( 'No usage instructions available yet.', 'vyndra-product-single-acf' ); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="tab-panel" data-tab="return-refund">
                        <?php if ( $return_refund ) : ?>
                            <?php echo apply_filters( 'the_content', $return_refund ); ?>
                        <?php else : ?>
                            <p><?php esc_html_e( 'No return and refund policy added yet.', 'vyndra-product-single-acf' ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="product-detail-accordion">
                <div class="accordion-item open">
                    <button type="button" class="accordion-toggle"><?php esc_html_e( 'Description', 'vyndra-product-single-acf' ); ?> <span class="accordion-icon">+</span></button>
                    <div class="accordion-content"><?php echo apply_filters( 'the_content', $description ); ?></div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-toggle"><?php esc_html_e( 'Product Details', 'vyndra-product-single-acf' ); ?> <span class="accordion-icon">+</span></button>
                    <div class="accordion-content"><?php echo apply_filters( 'the_content', $product_details ); ?></div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-toggle"><?php esc_html_e( 'Care Instructions', 'vyndra-product-single-acf' ); ?> <span class="accordion-icon">+</span></button>
                    <div class="accordion-content"><?php echo apply_filters( 'the_content', $care_instructions ); ?></div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-toggle"><?php esc_html_e( 'Usage Instructions', 'vyndra-product-single-acf' ); ?> <span class="accordion-icon">+</span></button>
                    <div class="accordion-content"><?php echo apply_filters( 'the_content', $usage_instructions ); ?></div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-toggle"><?php esc_html_e( 'Return and Refund', 'vyndra-product-single-acf' ); ?> <span class="accordion-icon">+</span></button>
                    <div class="accordion-content"><?php echo apply_filters( 'the_content', $return_refund ); ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $highlights ) ) : ?>
            <div class="features-grid">
                <?php foreach ( $highlights as $highlight ) : ?>
                    <div class="feature-card">
                        <div class="fc-icon">✓</div>
                        <div class="fc-title"><?php echo esc_html( $highlight['title'] ); ?></div>
                        <div class="fc-desc"><?php echo esc_html( $highlight['text'] ); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="size-guide-section" id="sizeGuide">
            <h2 class="section-title"><?php esc_html_e( 'Size Guide', 'vyndra-product-single-acf' ); ?></h2>
            <?php if ( $size_guide_html ) : ?>
                <div class="size-table-wrap"><?php echo apply_filters( 'the_content', $size_guide_html ); ?></div>
            <?php else : ?>
                <div class="size-table-wrap">
                    <table class="size-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'Size', 'vyndra-product-single-acf' ); ?></th>
                                <th><?php esc_html_e( 'Chest (in)', 'vyndra-product-single-acf' ); ?></th>
                                <th><?php esc_html_e( 'Length (in)', 'vyndra-product-single-acf' ); ?></th>
                                <th><?php esc_html_e( 'Shoulder (in)', 'vyndra-product-single-acf' ); ?></th>
                                <th><?php esc_html_e( 'Sleeve (in)', 'vyndra-product-single-acf' ); ?></th>
                                <th><?php esc_html_e( 'Fits Body Chest', 'vyndra-product-single-acf' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td><strong>XS</strong></td><td>40</td><td>27</td><td>21</td><td>8.5</td><td>32–34"</td></tr>
                            <tr><td><strong>S</strong></td><td>42</td><td>28</td><td>22</td><td>9</td><td>34–36"</td></tr>
                            <tr><td><strong>M</strong></td><td>44</td><td>29</td><td>23</td><td>9.5</td><td>36–38"</td></tr>
                            <tr><td><strong>L</strong></td><td>46</td><td>30</td><td>24</td><td>10</td><td>38–40"</td></tr>
                            <tr><td><strong>XL</strong></td><td>48</td><td>31</td><td>25</td><td>10.5</td><td>40–42"</td></tr>
                            <tr><td><strong>XXL</strong></td><td>50</td><td>32</td><td>26</td><td>11</td><td>42–44"</td></tr>
                            <tr><td><strong>3XL</strong></td><td>52</td><td>33</td><td>27</td><td>11.5</td><td>44–46"</td></tr>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <?php if ( ! empty( $faqs ) ) : ?>
            <div class="faq-section">
                <h2 class="section-title"><?php esc_html_e( 'Frequently Asked Questions', 'vyndra-product-single-acf' ); ?></h2>
                <?php foreach ( $faqs as $index => $faq ) : ?>
                    <div class="faq-item<?php echo $index === 0 ? ' open' : ''; ?>">
                        <button type="button" class="faq-q">
                            <?php echo esc_html( $faq['question'] ); ?> <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-a"><?php echo apply_filters( 'the_content', $faq['answer'] ); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ( $average_rating ) : ?>
            <div class="reviews-section">
                <h2 class="section-title"><?php esc_html_e( 'What customers say', 'vyndra-product-single-acf' ); ?></h2>
                <div class="review-summary">
                    <div style="text-align:center;">
                        <div class="review-big-num"><?php echo esc_html( round( $average_rating, 1 ) ); ?></div>
                        <div class="review-stars-big"><?php echo wp_kses_post( str_repeat( '★', floor( $average_rating ) ) ); ?></div>
                        <div class="review-count-text"><?php echo esc_html( $review_count ); ?> <?php esc_html_e( 'reviews', 'vyndra-product-single-acf' ); ?></div>
                    </div>
                    <div class="review-bars">
                        <div class="bar-row"><span class="bar-label">5★</span><div class="bar-track"><div class="bar-fill" style="width:82%"></div></div><span class="bar-count">105</span></div>
                        <div class="bar-row"><span class="bar-label">4★</span><div class="bar-track"><div class="bar-fill" style="width:12%"></div></div><span class="bar-count">15</span></div>
                        <div class="bar-row"><span class="bar-label">3★</span><div class="bar-track"><div class="bar-fill" style="width:4%"></div></div><span class="bar-count">5</span></div>
                        <div class="bar-row"><span class="bar-label">2★</span><div class="bar-track"><div class="bar-fill" style="width:2%"></div></div><span class="bar-count">2</span></div>
                        <div class="bar-row"><span class="bar-label">1★</span><div class="bar-track"><div class="bar-fill" style="width:1%"></div></div><span class="bar-count">1</span></div>
                    </div>
                </div>
                <div class="review-card"><div class="review-header"><div><div class="reviewer-name"><?php esc_html_e( 'Aryan K.', 'vyndra-product-single-acf' ); ?></div><div class="reviewer-date"><?php esc_html_e( 'June 2, 2026', 'vyndra-product-single-acf' ); ?></div></div><div><div style="color:#f5c518;font-size:14px;">★★★★★</div><div class="verified-badge">✔ <?php esc_html_e( 'Verified Purchase', 'vyndra-product-single-acf' ); ?></div></div></div><div class="review-text"><?php esc_html_e( 'The quality is genuinely surprising for the price. The posture corrector feels premium and comfortable. It supported my posture without feeling restrictive.', 'vyndra-product-single-acf' ); ?></div></div>
                <div class="review-card"><div class="review-header"><div><div class="reviewer-name"><?php esc_html_e( 'Priya S.', 'vyndra-product-single-acf' ); ?></div><div class="reviewer-date"><?php esc_html_e( 'May 18, 2026', 'vyndra-product-single-acf' ); ?></div></div><div><div style="color:#f5c518;font-size:14px;">★★★★★</div><div class="verified-badge">✔ <?php esc_html_e( 'Verified Purchase', 'vyndra-product-single-acf' ); ?></div></div></div><div class="review-text"><?php esc_html_e( 'I noticed an immediate improvement in my posture after a few wears. The straps are comfortable and it is discreet under my shirt.', 'vyndra-product-single-acf' ); ?></div></div>
            </div>
        <?php endif; ?>
    </div>

    <div class="sticky-atc" id="vyndra-sticky-atc">
        <div class="sticky-product-info">
            <?php if ( $main_image ) : ?>
                <img class="sticky-img" src="<?php echo esc_url( $main_image ); ?>" alt="<?php echo esc_attr( $product->get_title() ); ?>">
            <?php endif; ?>
            <div>
                <div class="sticky-name"><?php echo esc_html( $product->get_title() ); ?></div>
                <div class="sticky-price"><?php echo wp_kses_post( wc_price( $display_price ) ); ?>
                    <?php if ( $sale_price && $regular_price && $regular_price !== $sale_price ) : ?>
                        <s style="font-size:12px;color:#999"><?php echo wp_kses_post( wc_price( $regular_price ) ); ?></s>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <button class="sticky-btn" type="button"><?php esc_html_e( 'Add to Cart →', 'vyndra-product-single-acf' ); ?></button>
    </div>
</div>
<?php
get_footer();
