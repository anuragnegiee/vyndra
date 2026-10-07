<?php
/**
 * Plugin Name: Vyndra Product Single ACF
 * Plugin URI: https://example.com/
 * Description: Custom WooCommerce product page sections for Frido-style single product layout using ACF.
 * Version: 0.1.0
 * Author: Vyndra Dev
 * Text Domain: vyndra-product-single-acf
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'VYNDRA_PRODUCT_SINGLE_ACF_PATH' ) ) {
    define( 'VYNDRA_PRODUCT_SINGLE_ACF_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'VYNDRA_PRODUCT_SINGLE_ACF_URL' ) ) {
    define( 'VYNDRA_PRODUCT_SINGLE_ACF_URL', plugin_dir_url( __FILE__ ) );
}

add_action( 'acf/init', 'vyndra_register_product_acf_fields' );
add_action( 'init', 'vyndra_disable_default_wc_tabs', 20 );
add_action( 'woocommerce_before_single_product_summary', 'vyndra_render_frido_top_layout', 5 );
add_action( 'woocommerce_after_single_product_summary', 'vyndra_render_frido_sections', 5 );
add_action( 'wp_enqueue_scripts', 'vyndra_enqueue_product_assets' );
add_action( 'admin_init', 'vyndra_populate_product_2045_data' );
add_filter( 'template_include', 'vyndra_override_single_product_template', 999 );
register_activation_hook( __FILE__, 'vyndra_create_sample_product_data' );
register_activation_hook( __FILE__, 'vyndra_populate_product_2045_data' );

/**
 * Register local ACF field group for products.
 *
 * @return void
 */
function vyndra_register_product_acf_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key' => 'group_vyndra_frido_product_data',
        'title' => 'Frido Product Data',
        'fields' => array(
            array(
                'key' => 'field_vyndra_whatsapp_url',
                'label' => 'WhatsApp Link',
                'name' => 'frido_whatsapp_url',
                'type' => 'url',
                'instructions' => 'Link for Shop on WhatsApp button.',
            ),
            array(
                'key' => 'field_vyndra_video_call_url',
                'label' => 'Video Call Link',
                'name' => 'frido_video_call_url',
                'type' => 'url',
                'instructions' => 'Link for Shop on Video Call button.',
            ),
            array(
                'key' => 'field_vyndra_video_embed_url',
                'label' => 'Video Embed URL',
                'name' => 'frido_video_embed_url',
                'type' => 'url',
                'instructions' => 'A product video URL to display in the page section.',
            ),
            array(
                'key' => 'field_vyndra_feature_1',
                'label' => 'Feature 1',
                'name' => 'frido_feature_1',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_feature_2',
                'label' => 'Feature 2',
                'name' => 'frido_feature_2',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_feature_3',
                'label' => 'Feature 3',
                'name' => 'frido_feature_3',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_feature_4',
                'label' => 'Feature 4',
                'name' => 'frido_feature_4',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_timeline_1',
                'label' => 'Timeline Item 1',
                'name' => 'frido_timeline_1',
                'type' => 'text',
                'instructions' => 'Example: Jul 15th - Order Placed',
            ),
            array(
                'key' => 'field_vyndra_timeline_2',
                'label' => 'Timeline Item 2',
                'name' => 'frido_timeline_2',
                'type' => 'text',
                'instructions' => 'Example: Jul 15th - 16th Order Shipped',
            ),
            array(
                'key' => 'field_vyndra_timeline_3',
                'label' => 'Timeline Item 3',
                'name' => 'frido_timeline_3',
                'type' => 'text',
                'instructions' => 'Example: Jul 18th - 19th Estimated Delivery',
            ),
            array(
                'key' => 'field_vyndra_bank_offer_1',
                'label' => 'Bank Offer Image 1',
                'name' => 'frido_bank_offer_image_1',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_bank_offer_2',
                'label' => 'Bank Offer Image 2',
                'name' => 'frido_bank_offer_image_2',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_bank_offer_3',
                'label' => 'Bank Offer Image 3',
                'name' => 'frido_bank_offer_image_3',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_bank_offer_4',
                'label' => 'Bank Offer Image 4',
                'name' => 'frido_bank_offer_image_4',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_bank_offer_5',
                'label' => 'Bank Offer Image 5',
                'name' => 'frido_bank_offer_image_5',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_bank_offer_6',
                'label' => 'Bank Offer Image 6',
                'name' => 'frido_bank_offer_image_6',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_measure_fit_image',
                'label' => 'Measure Fit Image',
                'name' => 'frido_measure_fit_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_badge_1',
                'label' => 'Badge 1',
                'name' => 'frido_badge_1',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_badge_2',
                'label' => 'Badge 2',
                'name' => 'frido_badge_2',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_price_note',
                'label' => 'Price Note',
                'name' => 'frido_price_note',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_variant_color_label',
                'label' => 'Variant 1 Label',
                'name' => 'frido_variant_color_label',
                'type' => 'text',
                'default_value' => 'Color',
                'instructions' => 'Label for the first variant group.',
            ),
            array(
                'key' => 'field_vyndra_variant_color_options',
                'label' => 'Color Options',
                'name' => 'frido_variant_color_options',
                'type' => 'text',
                'instructions' => 'Comma-separated color variants, e.g. Red,Green,Blue.',
            ),
            array(
                'key' => 'field_vyndra_variant_size_label',
                'label' => 'Variant 2 Label',
                'name' => 'frido_variant_size_label',
                'type' => 'text',
                'default_value' => 'Size',
                'instructions' => 'Label for the second variant group.',
            ),
            array(
                'key' => 'field_vyndra_variant_size_options',
                'label' => 'Size Options',
                'name' => 'frido_variant_size_options',
                'type' => 'text',
                'instructions' => 'Comma-separated size variants, e.g. S,M,L.',
            ),
            array(
                'key' => 'field_vyndra_size_guide_html',
                'label' => 'Size Guide HTML',
                'name' => 'frido_size_guide_html',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'media_upload' => 0,
            ),
            array(
                'key' => 'field_vyndra_description',
                'label' => 'Description',
                'name' => 'frido_description',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'media_upload' => 0,
            ),
            array(
                'key' => 'field_vyndra_product_details',
                'label' => 'Product Details',
                'name' => 'frido_product_details',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'media_upload' => 0,
            ),
            array(
                'key' => 'field_vyndra_care_instructions',
                'label' => 'Care Instructions',
                'name' => 'frido_care_instructions',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'media_upload' => 0,
            ),
            array(
                'key' => 'field_vyndra_usage_instructions',
                'label' => 'Usage Instructions',
                'name' => 'frido_usage_instructions',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'media_upload' => 0,
            ),
            array(
                'key' => 'field_vyndra_return_refund',
                'label' => 'Return and Refund',
                'name' => 'frido_return_refund',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'media_upload' => 0,
            ),
            array(
                'key' => 'field_vyndra_highlight_1_image',
                'label' => 'Highlight 1 Image',
                'name' => 'frido_highlight_1_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_highlight_1_title',
                'label' => 'Highlight 1 Title',
                'name' => 'frido_highlight_1_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_highlight_1_text',
                'label' => 'Highlight 1 Text',
                'name' => 'frido_highlight_1_text',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_highlight_2_image',
                'label' => 'Highlight 2 Image',
                'name' => 'frido_highlight_2_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_highlight_2_title',
                'label' => 'Highlight 2 Title',
                'name' => 'frido_highlight_2_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_highlight_2_text',
                'label' => 'Highlight 2 Text',
                'name' => 'frido_highlight_2_text',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_highlight_3_image',
                'label' => 'Highlight 3 Image',
                'name' => 'frido_highlight_3_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_highlight_3_title',
                'label' => 'Highlight 3 Title',
                'name' => 'frido_highlight_3_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_highlight_3_text',
                'label' => 'Highlight 3 Text',
                'name' => 'frido_highlight_3_text',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_highlight_4_image',
                'label' => 'Highlight 4 Image',
                'name' => 'frido_highlight_4_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_highlight_4_title',
                'label' => 'Highlight 4 Title',
                'name' => 'frido_highlight_4_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_highlight_4_text',
                'label' => 'Highlight 4 Text',
                'name' => 'frido_highlight_4_text',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_action_1_image',
                'label' => 'See It In Action 1 Image',
                'name' => 'frido_action_1_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_action_1_text',
                'label' => 'See It In Action 1 Text',
                'name' => 'frido_action_1_text',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_action_2_image',
                'label' => 'See It In Action 2 Image',
                'name' => 'frido_action_2_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_action_2_text',
                'label' => 'See It In Action 2 Text',
                'name' => 'frido_action_2_text',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_action_3_image',
                'label' => 'See It In Action 3 Image',
                'name' => 'frido_action_3_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_action_3_text',
                'label' => 'See It In Action 3 Text',
                'name' => 'frido_action_3_text',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_action_4_image',
                'label' => 'See It In Action 4 Image',
                'name' => 'frido_action_4_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_vyndra_action_4_text',
                'label' => 'See It In Action 4 Text',
                'name' => 'frido_action_4_text',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_faq_1_question',
                'label' => 'FAQ 1 Question',
                'name' => 'frido_faq_1_question',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_faq_1_answer',
                'label' => 'FAQ 1 Answer',
                'name' => 'frido_faq_1_answer',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'media_upload' => 0,
            ),
            array(
                'key' => 'field_vyndra_faq_2_question',
                'label' => 'FAQ 2 Question',
                'name' => 'frido_faq_2_question',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_faq_2_answer',
                'label' => 'FAQ 2 Answer',
                'name' => 'frido_faq_2_answer',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'media_upload' => 0,
            ),
            array(
                'key' => 'field_vyndra_faq_3_question',
                'label' => 'FAQ 3 Question',
                'name' => 'frido_faq_3_question',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_faq_3_answer',
                'label' => 'FAQ 3 Answer',
                'name' => 'frido_faq_3_answer',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'media_upload' => 0,
            ),
            array(
                'key' => 'field_vyndra_faq_4_question',
                'label' => 'FAQ 4 Question',
                'name' => 'frido_faq_4_question',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_faq_4_answer',
                'label' => 'FAQ 4 Answer',
                'name' => 'frido_faq_4_answer',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'media_upload' => 0,
            ),
            array(
                'key' => 'field_vyndra_faq_5_question',
                'label' => 'FAQ 5 Question',
                'name' => 'frido_faq_5_question',
                'type' => 'text',
            ),
            array(
                'key' => 'field_vyndra_faq_5_answer',
                'label' => 'FAQ 5 Answer',
                'name' => 'frido_faq_5_answer',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'media_upload' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'product',
                ),
            ),
        ),
        'style' => 'seamless',
        'position' => 'normal',
        'menu_order' => 0,
    ) );
}

/**
 * Disable the WooCommerce default product data tabs.
 *
 * @return void
 */
function vyndra_disable_default_wc_tabs() {
    if ( class_exists( 'WooCommerce' ) ) {
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
    }
}

/**
 * Enqueue frontend CSS/JS for the Frido product page.
 *
 * @return void
 */
function vyndra_enqueue_product_assets() {
    if ( ! is_product() ) {
        return;
    }

    wp_enqueue_style(
        'vyndra-product-single-acf',
        VYNDRA_PRODUCT_SINGLE_ACF_URL . 'assets/css/frido-product-single.css',
        array(),
        '0.1.0'
    );

    wp_enqueue_script(
        'vyndra-product-single-js',
        VYNDRA_PRODUCT_SINGLE_ACF_URL . 'assets/js/frido-product-single.js',
        array(),
        '0.1.0',
        true
    );
}

function vyndra_override_single_product_template( $template ) {
    /*
     * Keep product 2045 on the site-wide Elementor Pro single-product
     * template. That is the template used by the supplied reference product,
     * so it keeps the gallery, product details, variation picker, tabs, and
     * related-products section consistent across the store.
     */
    return $template;
}

function vyndra_render_frido_top_layout() {
    if ( ! is_product() ) {
        return;
    }

    global $post;
    $product = wc_get_product( $post->ID );
    if ( ! $product ) {
        return;
    }

    $image_ids = vyndra_get_product_gallery_ids( $product );
    $main_image = '';
    if ( ! empty( $image_ids ) ) {
        $main_image = wp_get_attachment_image_url( $image_ids[0], 'large' );
    }
    if ( ! $main_image ) {
        $main_image = $product->get_image_id() ? wp_get_attachment_image_url( $product->get_image_id(), 'large' ) : '';
    }

    $badges = array_filter( array(
        vyndra_get_field_value( 'frido_badge_1', $post->ID ),
        vyndra_get_field_value( 'frido_badge_2', $post->ID ),
    ) );

    $price_note = vyndra_get_field_value( 'frido_price_note', $post->ID );
    $whatsapp_url = vyndra_get_field_value( 'frido_whatsapp_url', $post->ID );
    $video_call_url = vyndra_get_field_value( 'frido_video_call_url', $post->ID );

    echo '<div class="product-wrap">';

    echo '<div class="gallery" id="vyndra-gallery">';
    echo '<div class="main-img-placeholder" id="vyndra-main-img-wrap">';
    if ( $main_image ) {
        echo '<img class="main-img" id="vyndra-main-img" src="' . esc_url( $main_image ) . '" alt="' . esc_attr( $product->get_title() ) . '">';
    } else {
        echo '<div class="main-img-placeholder-inner">Image unavailable</div>';
    }
    echo '</div>';

    if ( ! empty( $image_ids ) ) {
        echo '<div class="thumbnails">';
        foreach ( $image_ids as $index => $image_id ) {
            $image_url = wp_get_attachment_image_url( $image_id, 'medium' );
            if ( ! $image_url ) {
                continue;
            }
            $active_class = $index === 0 ? ' active' : '';
            echo '<button type="button" class="thumb' . $active_class . '" data-full-src="' . esc_url( wp_get_attachment_image_url( $image_id, 'large' ) ) . '" aria-label="Thumbnail ' . ( $index + 1 ) . '">';
            echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $product->get_title() ) . '">';
            echo '</button>';
        }
        echo '</div>';
    }
    echo '</div>';

    echo '<div class="product-info">';
    if ( ! empty( $badges ) ) {
        echo '<div class="badge-row">';
        foreach ( $badges as $badge ) {
            echo '<span class="badge">' . esc_html( $badge ) . '</span>';
        }
        echo '</div>';
    }

    echo '<h1 class="product-title">' . esc_html( $product->get_title() ) . '</h1>';

    $average_rating = $product->get_average_rating();
    $review_count = $product->get_review_count();
    if ( $average_rating ) {
        echo '<div class="rating-row">';
        echo '<span class="stars">' . wp_kses_post( str_repeat( '★', floor( $average_rating ) ) ) . '</span>';
        echo '<span class="rating-count">' . esc_html( round( $average_rating, 1 ) ) . '</span>';
        if ( $review_count ) {
            echo '<span class="rating-link">' . esc_html( $review_count ) . ' reviews</span>';
        }
        echo '</div>';
    }

    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $display_price = $product->get_price();

    echo '<div class="price-row">';
    echo '<span class="price-current">' . wc_price( $display_price ) . '</span>';
    if ( $sale_price && $regular_price && $regular_price !== $sale_price ) {
        echo '<span class="price-original">' . wc_price( $regular_price ) . '</span>';
        $discount = round( ( 1 - ( $sale_price / $regular_price ) ) * 100 );
        if ( $discount > 0 ) {
            echo '<span class="price-discount">' . esc_html( $discount ) . '% OFF</span>';
        }
    }
    echo '</div>';
    if ( $price_note ) {
        echo '<p class="price-note">' . esc_html( $price_note ) . '</p>';
    }

    echo '<div class="divider"></div>';

    echo '<div class="section-label">Select Size<a class="size-guide-link" href="#sizeGuide">📏 Size Guide</a></div>';

    echo '<div class="size-block">';
    echo '<div class="vyndra-woocommerce-add-to-cart">';
    if ( function_exists( 'woocommerce_template_single_add_to_cart' ) ) {
        woocommerce_template_single_add_to_cart();
    }
    echo '</div>';
    echo '</div>';

    echo '<div class="cta-stack">';
    if ( $whatsapp_url ) {
        echo '<a class="btn-atc" href="' . esc_url( $whatsapp_url ) . '" target="_blank" rel="noopener">WhatsApp Order</a>';
    }
    if ( $video_call_url ) {
        echo '<a class="btn-buy" href="' . esc_url( $video_call_url ) . '" target="_blank" rel="noopener">Video Call</a>';
    }
    echo '</div>';

    echo '<div class="trust-strip">';
    echo '<div class="trust-item"><div class="trust-icon">🚚</div><div class="trust-label">Free Delivery</div><div class="trust-sub">Pan India</div></div>';
    echo '<div class="trust-item"><div class="trust-icon">↩️</div><div class="trust-label">7-Day Returns</div><div class="trust-sub">Hassle-free</div></div>';
    echo '<div class="trust-item"><div class="trust-icon">🔒</div><div class="trust-label">Secure Pay</div><div class="trust-sub">100% Safe</div></div>';
    echo '</div>';

    echo '<div class="product-info-footer">';
    echo '<div class="payment-row"><span class="payment-label">Pay with:</span><span class="pay-icon">UPI</span><span class="pay-icon">VISA</span><span class="pay-icon">MC</span><span class="pay-icon">COD</span><span class="pay-icon">EMI</span></div>';
    echo '</div>';

    echo '</div>'; // close product-info
    echo '</div>'; // close product-wrap
}

function vyndra_get_product_gallery_ids( $product ) {
    $image_ids = array();
    $featured_id = $product->get_image_id();
    if ( $featured_id ) {
        $image_ids[] = $featured_id;
    }
    $gallery_ids = $product->get_gallery_image_ids();
    if ( ! empty( $gallery_ids ) ) {
        $image_ids = array_merge( $image_ids, $gallery_ids );
    }
    return array_unique( $image_ids );
}

/**
 * Render the custom Frido single product sections.
 *
 * @return void
 */
function vyndra_render_frido_sections() {
    if ( ! is_product() ) {
        return;
    }

    global $post;
    $product = wc_get_product( $post->ID );
    if ( ! $product ) {
        return;
    }

    $highlights = array();
    for ( $i = 1; $i <= 4; $i++ ) {
        $title = vyndra_get_field_value( 'frido_highlight_' . $i . '_title', $post->ID );
        $text = vyndra_get_field_value( 'frido_highlight_' . $i . '_text', $post->ID );
        if ( $title || $text ) {
            $highlights[] = compact( 'title', 'text' );
        }
    }

    $size_guide_html = vyndra_get_field_value( 'frido_size_guide_html', $post->ID );

    $faqs = array();
    for ( $i = 1; $i <= 5; $i++ ) {
        $question = vyndra_get_field_value( 'frido_faq_' . $i . '_question', $post->ID );
        $answer = vyndra_get_field_value( 'frido_faq_' . $i . '_answer', $post->ID );
        if ( $question || $answer ) {
            $faqs[] = compact( 'question', 'answer' );
        }
    }

    $image_ids = vyndra_get_product_gallery_ids( $product );
    $main_image = '';
    if ( ! empty( $image_ids ) ) {
        $main_image = wp_get_attachment_image_url( $image_ids[0], 'thumbnail' );
    }

    $average_rating = $product->get_average_rating();
    $review_count = $product->get_review_count();
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $display_price = $product->get_price();

    echo '<div class="below-fold">';

    if ( ! empty( $highlights ) ) {
        echo '<h2 class="section-title">Built different. Feels different.</h2>';
        echo '<div class="features-grid">';
        foreach ( $highlights as $highlight ) {
            echo '<div class="feature-card">';
            echo '<div class="fc-icon">✓</div>';
            echo '<div class="fc-title">' . esc_html( $highlight['title'] ) . '</div>';
            echo '<div class="fc-desc">' . esc_html( $highlight['text'] ) . '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    echo '<div class="size-guide-section" id="sizeGuide">';
    echo '<h2 class="section-title">Size Guide</h2>';
    if ( $size_guide_html ) {
        echo '<div class="size-table-wrap">' . apply_filters( 'the_content', $size_guide_html ) . '</div>';
    } else {
        echo '<div class="size-table-wrap">';
        echo '<table class="size-table">';
        echo '<thead><tr><th>Size</th><th>Chest (in)</th><th>Length (in)</th><th>Shoulder (in)</th><th>Sleeve (in)</th><th>Fits Body Chest</th></tr></thead>';
        echo '<tbody>';
        echo '<tr><td><strong>XS</strong></td><td>40</td><td>27</td><td>21</td><td>8.5</td><td>32–34"</td></tr>';
        echo '<tr><td><strong>S</strong></td><td>42</td><td>28</td><td>22</td><td>9</td><td>34–36"</td></tr>';
        echo '<tr><td><strong>M</strong></td><td>44</td><td>29</td><td>23</td><td>9.5</td><td>36–38"</td></tr>';
        echo '<tr><td><strong>L</strong></td><td>46</td><td>30</td><td>24</td><td>10</td><td>38–40"</td></tr>';
        echo '<tr><td><strong>XL</strong></td><td>48</td><td>31</td><td>25</td><td>10.5</td><td>40–42"</td></tr>';
        echo '<tr><td><strong>XXL</strong></td><td>50</td><td>32</td><td>26</td><td>11</td><td>42–44"</td></tr>';
        echo '<tr><td><strong>3XL</strong></td><td>52</td><td>33</td><td>27</td><td>11.5</td><td>44–46"</td></tr>';
        echo '</tbody></table>';
        echo '</div>';
    }
    echo '</div>';

    if ( ! empty( $faqs ) ) {
        echo '<div class="faq-section"><h2 class="section-title">Frequently Asked Questions</h2>';
        foreach ( $faqs as $index => $faq ) {
            $open_class = $index === 0 ? ' open' : '';
            echo '<div class="vyndra-faq-item' . $open_class . '">';
            echo '<button type="button" class="vyndra-faq-q">' . esc_html( $faq['question'] ) . '<span class="faq-icon">+</span></button>';
            echo '<div class="vyndra-faq-a">' . apply_filters( 'the_content', $faq['answer'] ) . '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    if ( $average_rating ) {
        echo '<div class="reviews-section">';
        echo '<h2 class="section-title">What customers say</h2>';
        echo '<div class="review-summary">';
        echo '<div style="text-align:center;"><div class="review-big-num">' . esc_html( round( $average_rating, 1 ) ) . '</div><div class="review-stars-big">' . str_repeat( '★', floor( $average_rating ) ) . '</div><div class="review-count-text">' . esc_html( $review_count ) . ' reviews</div></div>';
        echo '<div class="review-bars">';
        echo '<div class="bar-row"><span class="bar-label">5★</span><div class="bar-track"><div class="bar-fill" style="width:82%"></div></div><span class="bar-count">105</span></div>';
        echo '<div class="bar-row"><span class="bar-label">4★</span><div class="bar-track"><div class="bar-fill" style="width:12%"></div></div><span class="bar-count">15</span></div>';
        echo '<div class="bar-row"><span class="bar-label">3★</span><div class="bar-track"><div class="bar-fill" style="width:4%"></div></div><span class="bar-count">5</span></div>';
        echo '<div class="bar-row"><span class="bar-label">2★</span><div class="bar-track"><div class="bar-fill" style="width:2%"></div></div><span class="bar-count">2</span></div>';
        echo '<div class="bar-row"><span class="bar-label">1★</span><div class="bar-track"><div class="bar-fill" style="width:1%"></div></div><span class="bar-count">1</span></div>';
        echo '</div></div>';
        echo '<div class="review-card"><div class="review-header"><div><div class="reviewer-name">Aryan K.</div><div class="reviewer-date">June 2, 2026</div></div><div><div style="color:#f5c518;font-size:14px;">★★★★★</div><div class="verified-badge">✔ Verified Purchase</div></div></div><div class="review-text">The quality is genuinely surprising for the price. The posture corrector feels premium and comfortable. It supported my posture without feeling restrictive.</div></div>';
        echo '<div class="review-card"><div class="review-header"><div><div class="reviewer-name">Priya S.</div><div class="reviewer-date">May 18, 2026</div></div><div><div style="color:#f5c518;font-size:14px;">★★★★★</div><div class="verified-badge">✔ Verified Purchase</div></div></div><div class="review-text">I noticed an immediate improvement in my posture after a few wears. The straps are comfortable and it is discreet under my shirt.</div></div>';
        echo '</div>';
    }

    echo '</div>';

    echo '<div class="sticky-atc" id="vyndra-sticky-atc">';
    echo '<div class="sticky-product-info">';
    if ( $main_image ) {
        echo '<img class="sticky-img" src="' . esc_url( $main_image ) . '" alt="' . esc_attr( $product->get_title() ) . '">';
    }
    echo '<div><div class="sticky-name">' . esc_html( $product->get_title() ) . '</div><div class="sticky-price">' . wc_price( $display_price );
    if ( $sale_price && $regular_price && $regular_price !== $sale_price ) {
        echo ' <s style="font-size:12px;color:#999">' . wc_price( $regular_price ) . '</s>';
    }
    echo '</div></div></div>';
    echo '<button class="sticky-btn" type="button">Add to Cart →</button>';
    echo '</div>';
}

/**
 * Output a single accordion block.
 *
 * @param string $title Accordion title.
 * @param string $content Accordion content.
 *
 * @return void
 */
function vyndra_render_accordion_item( $title, $content ) {
    if ( empty( $content ) ) {
        return;
    }

    echo '<details class="vyndra-accordion-item"><summary>' . esc_html( $title ) . '</summary><div class="vyndra-accordion-content">' . apply_filters( 'the_content', $content ) . '</div></details>';
}

/**
 * Get an ACF field value and fall back to post meta if needed.
 *
 * @param string $field_name Field name.
 * @param int    $post_id    Post ID.
 *
 * @return mixed
 */
function vyndra_get_field_value( $field_name, $post_id ) {
    if ( function_exists( 'get_field' ) ) {
        $value = get_field( $field_name, $post_id );
    } else {
        $value = get_post_meta( $post_id, $field_name, true );
    }
    return $value;
}

/**
 * Get an image URL from an ACF image field.
 *
 * @param int    $post_id Post ID.
 * @param string $field   Field name.
 * @param string $size    Image size.
 *
 * @return string
 */
function vyndra_get_image_url( $post_id, $field, $size = 'full' ) {
    $value = vyndra_get_field_value( $field, $post_id );
    if ( empty( $value ) ) {
        return '';
    }

    if ( is_numeric( $value ) ) {
        return wp_get_attachment_image_url( $value, $size );
    }

    if ( is_string( $value ) && filter_var( $value, FILTER_VALIDATE_URL ) ) {
        return $value;
    }

    return '';
}

/**
 * Create a global WooCommerce attribute if it does not already exist.
 *
 * @param string $name Attribute label.
 * @param string $slug Attribute slug.
 * @return void
 */
function vyndra_create_wc_attribute( $name, $slug ) {
    if ( ! function_exists( 'wc_create_attribute' ) ) {
        return;
    }

    $taxonomy = 'pa_' . $slug;
    if ( taxonomy_exists( $taxonomy ) ) {
        return;
    }

    $attribute_id = wc_create_attribute( array(
        'name'        => $name,
        'slug'        => $slug,
        'type'        => 'select',
        'order_by'    => 'menu_order',
        'has_archives' => false,
    ) );

    if ( $attribute_id && ! is_wp_error( $attribute_id ) ) {
        flush_rewrite_rules( false );
    }
}

/**
 * Create terms for a WooCommerce product attribute taxonomy.
 *
 * @param string $taxonomy Attribute taxonomy.
 * @param array  $terms Array of term names.
 * @return void
 */
function vyndra_create_wc_attribute_terms( $taxonomy, $terms ) {
    if ( ! taxonomy_exists( $taxonomy ) || empty( $terms ) ) {
        return;
    }

    foreach ( $terms as $term_name ) {
        if ( ! term_exists( $term_name, $taxonomy ) ) {
            wp_insert_term( $term_name, $taxonomy );
        }
    }
}

/**
 * Ensure the Frido product has the expected WC attributes and terms.
 *
 * @param int $product_id Product ID.
 * @return void
 */
function vyndra_ensure_product_2045_attributes( $product_id ) {
    $color_taxonomy = 'pa_color';
    $size_taxonomy  = 'pa_size';

    vyndra_create_wc_attribute( 'Color', 'color' );
    vyndra_create_wc_attribute( 'Size', 'size' );
    vyndra_create_wc_attribute_terms( $color_taxonomy, array( 'Red', 'Green', 'Blue' ) );
    vyndra_create_wc_attribute_terms( $size_taxonomy, array( 'S', 'M', 'L' ) );

    wp_set_object_terms( $product_id, array( 'Red', 'Green', 'Blue' ), $color_taxonomy, true );
    wp_set_object_terms( $product_id, array( 'S', 'M', 'L' ), $size_taxonomy, true );

    $current_attributes = get_post_meta( $product_id, '_product_attributes', true );
    if ( ! is_array( $current_attributes ) ) {
        $current_attributes = array();
    }

    if ( ! isset( $current_attributes[ $color_taxonomy ] ) ) {
        $current_attributes[ $color_taxonomy ] = array(
            'name'         => $color_taxonomy,
            'value'        => '',
            'position'     => 1,
            'is_visible'   => 1,
            'is_variation' => 0,
            'is_taxonomy'  => 1,
        );
    }

    if ( ! isset( $current_attributes[ $size_taxonomy ] ) ) {
        $current_attributes[ $size_taxonomy ] = array(
            'name'         => $size_taxonomy,
            'value'        => '',
            'position'     => 2,
            'is_visible'   => 1,
            'is_variation' => 0,
            'is_taxonomy'  => 1,
        );
    }

    update_post_meta( $product_id, '_product_attributes', $current_attributes );
}

/**
 * Populate ACF data for existing product ID 2045, if present.
 *
 * @return void
 */
function vyndra_populate_product_2045_data() {
    if ( ! is_admin() && ! wp_doing_ajax() ) {
        return;
    }

    $product_id = 2045;
    $product = wc_get_product( $product_id );
    if ( ! $product || 'product' !== get_post_type( $product_id ) ) {
        return;
    }

    $already_populated = get_option( 'vyndra_product_2045_data_populated' );

    if ( ! $already_populated ) {
        update_post_meta( $product_id, 'frido_badge_1', '⭐ Bestseller' );
        update_post_meta( $product_id, 'frido_badge_2', '🔥 Limited Stock' );
        update_post_meta( $product_id, 'frido_price_note', 'Inclusive of all taxes | Free delivery on this order' );
        update_post_meta( $product_id, 'frido_variant_color_label', 'Color' );
        update_post_meta( $product_id, 'frido_variant_color_options', 'Red,Green,Blue' );
        update_post_meta( $product_id, 'frido_variant_size_label', 'Size' );
        update_post_meta( $product_id, 'frido_variant_size_options', 'S,M,L' );

        $size_guide_html = '<p style="font-size:13px;color:#6b6b6b;margin-bottom:18px;">All measurements are in inches. The fit is oversized — size down if you prefer a semi-relaxed look.</p><div class="size-table-wrap"><table class="size-table"><thead><tr><th>Size</th><th>Chest (in)</th><th>Length (in)</th><th>Shoulder (in)</th><th>Sleeve (in)</th><th>Fits Body Chest</th></tr></thead><tbody><tr><td><strong>XS</strong></td><td>40</td><td>27</td><td>21</td><td>8.5</td><td>32–34"</td></tr><tr><td><strong>S</strong></td><td>42</td><td>28</td><td>22</td><td>9</td><td>34–36"</td></tr><tr><td><strong>M</strong></td><td>44</td><td>29</td><td>23</td><td>9.5</td><td>36–38"</td></tr><tr><td><strong>L</strong></td><td>46</td><td>30</td><td>24</td><td>10</td><td>38–40"</td></tr><tr><td><strong>XL</strong></td><td>48</td><td>31</td><td>25</td><td>10.5</td><td>40–42"</td></tr><tr><td><strong>XXL</strong></td><td>50</td><td>32</td><td>26</td><td>11</td><td>42–44"</td></tr><tr><td><strong>3XL</strong></td><td>52</td><td>33</td><td>27</td><td>11.5</td><td>44–46"</td></tr></tbody></table></div>';
        update_post_meta( $product_id, 'frido_size_guide_html', $size_guide_html );

        update_post_meta( $product_id, 'frido_feature_1', 'Enhanced Posture Correction' );
        update_post_meta( $product_id, 'frido_feature_2', 'Invisible Under Clothes' );
        update_post_meta( $product_id, 'frido_feature_3', 'EVA-Padded Underarm Straps' );
        update_post_meta( $product_id, 'frido_feature_4', 'Octa-Pore Breathable Lining' );

        update_post_meta( $product_id, 'frido_highlight_1_title', 'Premium Support' );
        update_post_meta( $product_id, 'frido_highlight_1_text', 'Delivers gentle posture correction with a near-invisible fit.' );
        update_post_meta( $product_id, 'frido_highlight_2_title', 'Breathable Comfort' );
        update_post_meta( $product_id, 'frido_highlight_2_text', 'Octa-Pore lining lets air circulate, keeping sweat and heat at bay.' );
        update_post_meta( $product_id, 'frido_highlight_3_title', 'Easy Adjustment' );
        update_post_meta( $product_id, 'frido_highlight_3_text', 'Soft, ergonomic straps adjust quickly and stay secure.' );
        update_post_meta( $product_id, 'frido_highlight_4_title', 'Discreet Wear' );
        update_post_meta( $product_id, 'frido_highlight_4_text', 'Designed to sit smoothly beneath shirts and tees without bulk.' );

        update_post_meta( $product_id, 'frido_faq_1_question', 'How long should I wear it first?' );
        update_post_meta( $product_id, 'frido_faq_1_answer', '<p>Start with 20-30 minutes daily, then increase gradually until it feels natural.</p>' );
        update_post_meta( $product_id, 'frido_faq_2_question', 'Can I wear it under a shirt?' );
        update_post_meta( $product_id, 'frido_faq_2_answer', '<p>Yes — the low-profile design fits comfortably beneath tees and tops.</p>' );
        update_post_meta( $product_id, 'frido_faq_3_question', 'Is it suitable for daily use?' );
        update_post_meta( $product_id, 'frido_faq_3_answer', '<p>Absolutely. It is built for regular wear during work, walking, or light activity.</p>' );
        update_post_meta( $product_id, 'frido_faq_4_question', 'How do I clean it?' );
        update_post_meta( $product_id, 'frido_faq_4_answer', '<p>Hand wash gently with mild detergent and air dry flat.</p>' );
        update_post_meta( $product_id, 'frido_faq_5_question', 'What is the return policy?' );
        update_post_meta( $product_id, 'frido_faq_5_answer', '<p>7-day returns on unworn products with the original packaging.</p>' );

        $image_urls = array(
            'https://vyndra.in/wp-content/uploads/2026/02/Front_1_c_57-3-topaz-upscale-4x-600x770.jpg',
            'https://vyndra.in/wp-content/uploads/2026/02/Left_Shoulder_5_c_57-1-topaz-face-upscale-4x-600x770.jpg',
            'https://vyndra.in/wp-content/uploads/2026/02/ChatGPT-Image-Feb-10-2026-06_21_45-AM-600x900.png',
            'https://vyndra.in/wp-content/uploads/2026/02/1744976688TerryteeSizeChart-19-600x774.png',
        );

        $attachment_ids = array();
        foreach ( $image_urls as $index => $url ) {
            $attachment_id = vyndra_sideload_image( $url, $product_id );
            if ( $attachment_id ) {
                $attachment_ids[] = $attachment_id;
                if ( 0 === $index ) {
                    set_post_thumbnail( $product_id, $attachment_id );
                }
            }
        }

        if ( ! empty( $attachment_ids ) ) {
            update_post_meta( $product_id, '_product_image_gallery', implode( ',', $attachment_ids ) );
            update_post_meta( $product_id, 'frido_bank_offer_image_1', $attachment_ids[0] );
            if ( isset( $attachment_ids[1] ) ) {
                update_post_meta( $product_id, 'frido_bank_offer_image_2', $attachment_ids[1] );
            }
        }

        update_post_meta( $product_id, 'frido_whatsapp_url', 'https://api.whatsapp.com/send/?phone=917973068848&text=Hi%20Vyndra,%20I%27m%20interested%20in%20Frido%20Orthotics%20Posture%20Corrector%20Pro' );
        update_post_meta( $product_id, 'frido_video_call_url', 'https://api.whatsapp.com/send/?phone=917973068848&text=Hi%20Vyndra,%20I%27d%20like%20a%20video%20call%20for%20Frido%20Orthotics%20Posture%20Corrector%20Pro' );

        update_option( 'vyndra_product_2045_data_populated', 1 );
    }

    vyndra_ensure_product_2045_attributes( $product_id );
    update_post_meta( $product_id, 'frido_badge_2', '🔥 Limited Stock' );
    update_post_meta( $product_id, 'frido_price_note', 'Inclusive of all taxes | Free delivery on this order' );
    update_post_meta( $product_id, 'frido_variant_color_label', 'Color' );
    update_post_meta( $product_id, 'frido_variant_color_options', 'Red,Green,Blue' );
    update_post_meta( $product_id, 'frido_variant_size_label', 'Size' );
    update_post_meta( $product_id, 'frido_variant_size_options', 'S,M,L' );

    $color_taxonomy = 'pa_color';
    $size_taxonomy  = 'pa_size';

    vyndra_create_wc_attribute( 'Color', 'color' );
    vyndra_create_wc_attribute( 'Size', 'size' );
    vyndra_create_wc_attribute_terms( $color_taxonomy, array( 'Red', 'Green', 'Blue' ) );
    vyndra_create_wc_attribute_terms( $size_taxonomy, array( 'S', 'M', 'L' ) );

    wp_set_object_terms( $product_id, array( 'Red', 'Green', 'Blue' ), $color_taxonomy, true );
    wp_set_object_terms( $product_id, array( 'S', 'M', 'L' ), $size_taxonomy, true );

    update_post_meta( $product_id, '_product_attributes', array(
        $color_taxonomy => array(
            'name'        => $color_taxonomy,
            'value'       => '',
            'position'    => 1,
            'is_visible'  => 1,
            'is_variation'=> 0,
            'is_taxonomy' => 1,
        ),
        $size_taxonomy => array(
            'name'        => $size_taxonomy,
            'value'       => '',
            'position'    => 2,
            'is_visible'  => 1,
            'is_variation'=> 0,
            'is_taxonomy' => 1,
        ),
    ) );

    $size_guide_html = '<p style="font-size:13px;color:#6b6b6b;margin-bottom:18px;">All measurements are in inches. The fit is oversized — size down if you prefer a semi-relaxed look.</p><div class="size-table-wrap"><table class="size-table"><thead><tr><th>Size</th><th>Chest (in)</th><th>Length (in)</th><th>Shoulder (in)</th><th>Sleeve (in)</th><th>Fits Body Chest</th></tr></thead><tbody><tr><td><strong>XS</strong></td><td>40</td><td>27</td><td>21</td><td>8.5</td><td>32–34"</td></tr><tr><td><strong>S</strong></td><td>42</td><td>28</td><td>22</td><td>9</td><td>34–36"</td></tr><tr><td><strong>M</strong></td><td>44</td><td>29</td><td>23</td><td>9.5</td><td>36–38"</td></tr><tr><td><strong>L</strong></td><td>46</td><td>30</td><td>24</td><td>10</td><td>38–40"</td></tr><tr><td><strong>XL</strong></td><td>48</td><td>31</td><td>25</td><td>10.5</td><td>40–42"</td></tr><tr><td><strong>XXL</strong></td><td>50</td><td>32</td><td>26</td><td>11</td><td>42–44"</td></tr><tr><td><strong>3XL</strong></td><td>52</td><td>33</td><td>27</td><td>11.5</td><td>44–46"</td></tr></tbody></table></div>';
    update_post_meta( $product_id, 'frido_size_guide_html', $size_guide_html );

    update_post_meta( $product_id, 'frido_feature_1', 'Enhanced Posture Correction' );
    update_post_meta( $product_id, 'frido_feature_2', 'Invisible Under Clothes' );
    update_post_meta( $product_id, 'frido_feature_3', 'EVA-Padded Underarm Straps' );
    update_post_meta( $product_id, 'frido_feature_4', 'Octa-Pore Breathable Lining' );

    update_post_meta( $product_id, 'frido_highlight_1_title', 'Premium Support' );
    update_post_meta( $product_id, 'frido_highlight_1_text', 'Delivers gentle posture correction with a near-invisible fit.' );
    update_post_meta( $product_id, 'frido_highlight_2_title', 'Breathable Comfort' );
    update_post_meta( $product_id, 'frido_highlight_2_text', 'Octa-Pore lining lets air circulate, keeping sweat and heat at bay.' );
    update_post_meta( $product_id, 'frido_highlight_3_title', 'Easy Adjustment' );
    update_post_meta( $product_id, 'frido_highlight_3_text', 'Soft, ergonomic straps adjust quickly and stay secure.' );
    update_post_meta( $product_id, 'frido_highlight_4_title', 'Discreet Wear' );
    update_post_meta( $product_id, 'frido_highlight_4_text', 'Designed to sit smoothly beneath shirts and tees without bulk.' );

    update_post_meta( $product_id, 'frido_faq_1_question', 'How long should I wear it first?' );
    update_post_meta( $product_id, 'frido_faq_1_answer', '<p>Start with 20-30 minutes daily, then increase gradually until it feels natural.</p>' );
    update_post_meta( $product_id, 'frido_faq_2_question', 'Can I wear it under a shirt?' );
    update_post_meta( $product_id, 'frido_faq_2_answer', '<p>Yes — the low-profile design fits comfortably beneath tees and tops.</p>' );
    update_post_meta( $product_id, 'frido_faq_3_question', 'Is it suitable for daily use?' );
    update_post_meta( $product_id, 'frido_faq_3_answer', '<p>Absolutely. It is built for regular wear during work, walking, or light activity.</p>' );
    update_post_meta( $product_id, 'frido_faq_4_question', 'How do I clean it?' );
    update_post_meta( $product_id, 'frido_faq_4_answer', '<p>Hand wash gently with mild detergent and air dry flat.</p>' );
    update_post_meta( $product_id, 'frido_faq_5_question', 'What is the return policy?' );
    update_post_meta( $product_id, 'frido_faq_5_answer', '<p>7-day returns on unworn products with the original packaging.</p>' );

    $image_urls = array(
        'https://vyndra.in/wp-content/uploads/2026/02/Front_1_c_57-3-topaz-upscale-4x-600x770.jpg',
        'https://vyndra.in/wp-content/uploads/2026/02/Left_Shoulder_5_c_57-1-topaz-face-upscale-4x-600x770.jpg',
        'https://vyndra.in/wp-content/uploads/2026/02/ChatGPT-Image-Feb-10-2026-06_21_45-AM-600x900.png',
        'https://vyndra.in/wp-content/uploads/2026/02/1744976688TerryteeSizeChart-19-600x774.png',
    );

    $attachment_ids = array();
    foreach ( $image_urls as $index => $url ) {
        $attachment_id = vyndra_sideload_image( $url, $product_id );
        if ( $attachment_id ) {
            $attachment_ids[] = $attachment_id;
            if ( 0 === $index ) {
                set_post_thumbnail( $product_id, $attachment_id );
            }
        }
    }

    if ( ! empty( $attachment_ids ) ) {
        update_post_meta( $product_id, '_product_image_gallery', implode( ',', $attachment_ids ) );
        update_post_meta( $product_id, 'frido_bank_offer_image_1', $attachment_ids[0] );
        if ( isset( $attachment_ids[1] ) ) {
            update_post_meta( $product_id, 'frido_bank_offer_image_2', $attachment_ids[1] );
        }
    }

    update_post_meta( $product_id, 'frido_whatsapp_url', 'https://api.whatsapp.com/send/?phone=917973068848&text=Hi%20Vyndra,%20I%27m%20interested%20in%20Frido%20Orthotics%20Posture%20Corrector%20Pro' );
    update_post_meta( $product_id, 'frido_video_call_url', 'https://api.whatsapp.com/send/?phone=917973068848&text=Hi%20Vyndra,%20I%27d%20like%20a%20video%20call%20for%20Frido%20Orthotics%20Posture%20Corrector%20Pro' );

    update_option( 'vyndra_product_2045_data_populated', 1 );
}

/**
 * Create sample Frido product data and ACF values on plugin activation.
 *
 * @return void
 */
function vyndra_create_sample_product_data() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    $slug = 'frido-orthotics-posture-corrector-pro';
    if ( get_page_by_path( $slug, OBJECT, 'product' ) ) {
        return;
    }

    $attribute_name = 'size';
    $attribute_taxonomy = 'pa_' . $attribute_name;

    if ( ! taxonomy_exists( $attribute_taxonomy ) && function_exists( 'wc_create_attribute' ) ) {
        $attribute_id = wc_create_attribute( array(
            'name' => ucfirst( $attribute_name ),
            'slug' => $attribute_name,
            'type' => 'select',
            'order_by' => 'menu_order',
            'has_archives' => false,
        ) );
        if ( $attribute_id && ! is_wp_error( $attribute_id ) ) {
            flush_rewrite_rules( false );
        }
    }

    $sizes = array( 'S', 'M', 'L', 'XL' );
    if ( taxonomy_exists( $attribute_taxonomy ) ) {
        foreach ( $sizes as $size ) {
            if ( ! term_exists( $size, $attribute_taxonomy ) ) {
                wp_insert_term( $size, $attribute_taxonomy );
            }
        }
    }

    $product_id = wp_insert_post( array(
        'post_title' => 'Frido Orthotics Posture Corrector Pro',
        'post_name'  => $slug,
        'post_content' => 'The Frido Orthotics Posture Corrector Pro supports better posture, reduces slouching, and now includes multiple data-driven sections via ACF.',
        'post_excerpt' => 'Breathable Octa-Pore fabric reduces sweat build-up while providing gentle posture correction.',
        'post_status' => 'publish',
        'post_type' => 'product',
        'meta_input' => array(
            '_visibility' => 'visible',
            '_stock_status' => 'instock',
            '_regular_price' => '999',
            '_price' => '999',
        ),
    ) );

    if ( is_wp_error( $product_id ) || ! $product_id ) {
        return;
    }

    if ( taxonomy_exists( $attribute_taxonomy ) ) {
        wp_set_object_terms( $product_id, $sizes, $attribute_taxonomy );
        $product = wc_get_product( $product_id );
        if ( $product ) {
            $attribute = new WC_Product_Attribute();
            $attribute->set_id( wc_attribute_taxonomy_id_by_name( $attribute_taxonomy ) );
            $attribute->set_name( $attribute_taxonomy );
            $attribute->set_options( $sizes );
            $attribute->set_position( 0 );
            $attribute->set_visible( true );
            $attribute->set_variation( true );
            $product->set_attributes( array( $attribute ) );
            $product->set_catalog_visibility( 'visible' );
            $product->set_status( 'publish' );
            $product->save();
        }
    }

    $image_urls = array(
        'https://cdn.shopify.com/s/files/1/0553/0419/2034/files/HDFC_Bank_offer.png?v=1780569458',
        'https://cdn.shopify.com/s/files/1/0553/0419/2034/files/HDFC_Bank_offer_127832a6-0518-47e9-bfe6-0f3b5a02f2d7.png?v=1781684378',
        'https://cdn.shopify.com/s/files/1/0553/0419/2034/files/500fgp.png?v=1771139460',
        'https://cdn.shopify.com/s/files/1/0553/0419/2034/files/fgp1000m.png?v=1771141138',
        'https://cdn.shopify.com/s/files/1/0553/0419/2034/files/snapmint_m.png?v=1771141286',
        'https://cdn.shopify.com/s/files/1/0553/0419/2034/files/Flat25UPI_1.png?v=1781001091',
    );

    $measure_image_url = 'https://cdn.shopify.com/s/files/1/0553/0419/2034/files/PostureCorrector_HowtoMeasure_4196ca61-3840-4dea-b10b-2ddb427f203a.jpg?v=1781197077';
    $highlight_image_urls = array(
        'https://via.placeholder.com/320x240.png?text=Posture+Correction',
        'https://via.placeholder.com/320x240.png?text=Straps+Comfort',
        'https://via.placeholder.com/320x240.png?text=Breathable+Fabric',
        'https://via.placeholder.com/320x240.png?text=Invisible+Under+Clothes',
    );
    $action_image_urls = array(
        'https://via.placeholder.com/320x240.png?text=See+It+1',
        'https://via.placeholder.com/320x240.png?text=See+It+2',
        'https://via.placeholder.com/320x240.png?text=See+It+3',
        'https://via.placeholder.com/320x240.png?text=See+It+4',
    );

    $featured_image_id = vyndra_sideload_image( 'https://via.placeholder.com/800x800.png?text=Frido+Orthotics+Posture+Corrector+Pro', $product_id );
    if ( $featured_image_id ) {
        set_post_thumbnail( $product_id, $featured_image_id );
    }

    $gallery_ids = array();
    foreach ( $action_image_urls as $url ) {
        $attachment_id = vyndra_sideload_image( $url, $product_id );
        if ( $attachment_id ) {
            $gallery_ids[] = $attachment_id;
        }
    }
    if ( ! empty( $gallery_ids ) ) {
        update_post_meta( $product_id, '_product_image_gallery', implode( ',', $gallery_ids ) );
    }

    foreach ( $image_urls as $index => $url ) {
        $attachment_id = vyndra_sideload_image( $url, $product_id );
        if ( $attachment_id ) {
            update_post_meta( $product_id, 'frido_bank_offer_image_' . ( $index + 1 ), $attachment_id );
        }
    }

    $measure_attachment = vyndra_sideload_image( $measure_image_url, $product_id );
    if ( $measure_attachment ) {
        update_post_meta( $product_id, 'frido_measure_fit_image', $measure_attachment );
    }

    foreach ( $highlight_image_urls as $index => $url ) {
        $attachment_id = vyndra_sideload_image( $url, $product_id );
        if ( $attachment_id ) {
            update_post_meta( $product_id, 'frido_highlight_' . ( $index + 1 ) . '_image', $attachment_id );
        }
    }

    foreach ( $action_image_urls as $index => $url ) {
        $attachment_id = vyndra_sideload_image( $url, $product_id );
        if ( $attachment_id ) {
            update_post_meta( $product_id, 'frido_action_' . ( $index + 1 ) . '_image', $attachment_id );
        }
    }

    update_post_meta( $product_id, 'frido_whatsapp_url', 'https://api.whatsapp.com/send/?phone=917028492155&text=Hello,%20I%27m%20interested%20in%20buying%20Frido%20Orthotics%20Posture%20Corrector%20Pro%20through%20WhatsApp!&type=phone_number&app_absent=0' );
    update_post_meta( $product_id, 'frido_video_call_url', 'https://api.whatsapp.com/send/?phone=917758957768&text=Hello,%20I%27m%20interested%20in%20buying%20Frido%20Orthotics%20Posture%20Corrector%20Pro%20through%20Video%20Call!%20&type=phone_number&app_absent=0' );
    update_post_meta( $product_id, 'frido_price_note', 'Inclusive of all taxes | Free delivery on this order' );
    update_post_meta( $product_id, 'frido_badge_1', '⭐ Bestseller' );
    update_post_meta( $product_id, 'frido_badge_2', '🔥 Limited Stock' );
    update_post_meta( $product_id, 'frido_size_guide_html', '<p style="font-size:13px;color:#6b6b6b;margin-bottom:18px;">All measurements are in inches. The fit is oversized — size down if you prefer a semi-relaxed look.</p><div class="size-table-wrap"><table class="size-table"><thead><tr><th>Size</th><th>Chest (in)</th><th>Length (in)</th><th>Shoulder (in)</th><th>Sleeve (in)</th><th>Fits Body Chest</th></tr></thead><tbody><tr><td><strong>XS</strong></td><td>40</td><td>27</td><td>21</td><td>8.5</td><td>32–34"</td></tr><tr><td><strong>S</strong></td><td>42</td><td>28</td><td>22</td><td>9</td><td>34–36"</td></tr><tr><td><strong>M</strong></td><td>44</td><td>29</td><td>23</td><td>9.5</td><td>36–38"</td></tr><tr><td><strong>L</strong></td><td>46</td><td>30</td><td>24</td><td>10</td><td>38–40"</td></tr><tr><td><strong>XL</strong></td><td>48</td><td>31</td><td>25</td><td>10.5</td><td>40–42"</td></tr><tr><td><strong>XXL</strong></td><td>50</td><td>32</td><td>26</td><td>11</td><td>42–44"</td></tr><tr><td><strong>3XL</strong></td><td>52</td><td>33</td><td>27</td><td>11.5</td><td>44–46"</td></tr></tbody></table></div>' );
    update_post_meta( $product_id, 'frido_video_embed_url', 'https://www.youtube.com/embed/dQw4w9WgXcQ' );
    update_post_meta( $product_id, 'frido_feature_1', 'Enhanced Posture Correction' );
    update_post_meta( $product_id, 'frido_feature_2', 'Invisible Under Clothes' );
    update_post_meta( $product_id, 'frido_feature_3', 'EVA-Padded Underarm Straps' );
    update_post_meta( $product_id, 'frido_feature_4', 'Octa-Pore Breathable Lining' );
    update_post_meta( $product_id, 'frido_timeline_1', 'Jul 15th - Order Placed' );
    update_post_meta( $product_id, 'frido_timeline_2', 'Jul 15th - 16th Order Shipped' );
    update_post_meta( $product_id, 'frido_timeline_3', 'Jul 18th - 19th Estimated Delivery' );
    update_post_meta( $product_id, 'frido_description', '<p>Frido Orthotics Posture Corrector Pro is engineered to help you keep your posture aligned and comfortable throughout the day.</p>' );
    update_post_meta( $product_id, 'frido_product_details', '<ul><li>Soft, ergonomic straps for even pressure distribution.</li><li>Stable posture support for daily walking, working, and exercise.</li></ul>' );
    update_post_meta( $product_id, 'frido_care_instructions', '<p>Hand wash gently with mild detergent and air dry. Do not machine wash or bleach.</p>' );
    update_post_meta( $product_id, 'frido_usage_instructions', '<p>Wear the posture corrector over a thin shirt. Start with 30 minutes per day and increase to 2 hours as needed.</p>' );
    update_post_meta( $product_id, 'frido_return_refund', '<p>30-day return policy on unopened products. Contact support for replacements or refunds.</p>' );

    foreach ( array( 'Enhanced Posture Correction', 'Invisible Under Clothes', 'EVA-Padded Underarm Straps', 'Octa-Pore Breathable Lining' ) as $index => $title ) {
        update_post_meta( $product_id, 'frido_highlight_' . ( $index + 1 ) . '_title', $title );
        update_post_meta( $product_id, 'frido_highlight_' . ( $index + 1 ) . '_text', 'Feature description for ' . $title . '.' );
    }

    foreach ( array( 'Unbox the posture corrector', 'Slip it on and adjust the straps', 'Walk comfortably with support', 'Feel confident and aligned' ) as $index => $text ) {
        update_post_meta( $product_id, 'frido_action_' . ( $index + 1 ) . '_text', $text );
    }

    update_post_meta( $product_id, 'frido_faq_1_question', 'How long should it be worn initially?' );
    update_post_meta( $product_id, 'frido_faq_1_answer', '<p>Start with 20-30 minutes during the first week, then gradually increase wear time.</p>' );
    update_post_meta( $product_id, 'frido_faq_2_question', 'Will it feel tight at first?' );
    update_post_meta( $product_id, 'frido_faq_2_answer', '<p>A little snugness is normal, but it should not restrict breathing or movement.</p>' );
    update_post_meta( $product_id, 'frido_faq_3_question', 'Does it restrict movement?' );
    update_post_meta( $product_id, 'frido_faq_3_answer', '<p>No. It supports posture without locking your shoulders into place.</p>' );
    update_post_meta( $product_id, 'frido_faq_4_question', 'Should it be worn directly on bare skin?' );
    update_post_meta( $product_id, 'frido_faq_4_answer', '<p>For maximum comfort, wear it over a thin tee or inner layer.</p>' );
    update_post_meta( $product_id, 'frido_faq_5_question', 'How should it be cleaned?' );
    update_post_meta( $product_id, 'frido_faq_5_answer', '<p>Hand wash only and air dry flat.</p>' );

    if ( post_type_exists( 'size-chart' ) ) {
        $chart_post_id = wp_insert_post( array(
            'post_type' => 'size-chart',
            'post_title' => 'Frido Posture Corrector Size Guide',
            'post_status' => 'publish',
            'post_content' => '<h2>Size Guide</h2><p>Select the right size based on chest and waist measurements.</p><table><tr><th>Size</th><th>Chest</th><th>Waist</th></tr><tr><td>S</td><td>32-34 in</td><td>26-28 in</td></tr><tr><td>M</td><td>34-36 in</td><td>28-30 in</td></tr><tr><td>L</td><td>36-38 in</td><td>30-32 in</td></tr><tr><td>XL</td><td>38-40 in</td><td>32-34 in</td></tr></table>',
        ) );
        if ( $chart_post_id && ! is_wp_error( $chart_post_id ) ) {
            update_post_meta( $product_id, 'prod-chart', $chart_post_id );
        }
    }

    if ( $product_id && class_exists( 'WC_Product_Variation' ) && taxonomy_exists( $attribute_taxonomy ) ) {
        foreach ( $sizes as $size ) {
            $variation = new WC_Product_Variation();
            $variation->set_parent_id( $product_id );
            $variation->set_status( 'publish' );
            $variation->set_regular_price( '999' );
            $variation->set_attributes( array( $attribute_taxonomy => $size ) );
            $variation->save();
        }
    }
}

/**
 * Sideload an image to a post.
 *
 * @param string $image_url Remote image URL.
 * @param int    $post_id   Post ID.
 *
 * @return int|false
 */
function vyndra_sideload_image( $image_url, $post_id ) {
    if ( ! function_exists( 'media_sideload_image' ) ) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
    }

    $tmp = download_url( $image_url );
    if ( is_wp_error( $tmp ) ) {
        return false;
    }

    $file = array(
        'name'     => basename( parse_url( $image_url, PHP_URL_PATH ) ),
        'tmp_name' => $tmp,
    );

    $id = media_handle_sideload( $file, $post_id );

    if ( is_wp_error( $id ) ) {
        @unlink( $tmp );
        return false;
    }

    return $id;
}
