<?php defined( 'ABSPATH' ) || exit; ?>
<footer class="vr-footer">
    <div class="vr-container vr-footer-top">
        <div class="vr-footer-brand"><a class="vr-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo vr_image( vr_setting( 'logo' ), 'medium', array( 'alt' => 'Vyndra' ) ); ?></a><p>More room.<br>More you.</p><span>Everyday style. Expressed your way.</span></div>
        <div><h2>Find your fit</h2><a href="<?php echo esc_url( vr_shop_url() ); ?>">Shop all</a><a href="<?php echo esc_url( vr_collection_url() ); ?>">Oversized tees</a><a href="<?php echo esc_url( vr_xxl_url() ); ?>">The XXL edit</a><a href="<?php echo esc_url( vr_page_url( 'about-us' ) ); ?>">About us</a></div>
        <div><h2>Here to help</h2><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">My account &amp; orders</a><a href="<?php echo esc_url( wc_get_cart_url() ); ?>">Your cart</a><a href="<?php echo esc_url( vr_page_url( 'privacy-refund-return-policy' ) ); ?>">Shipping &amp; returns</a><a href="<?php echo esc_url( vr_page_url( 'privacy-refund-return-policy' ) ); ?>">Privacy policy</a><a href="<?php echo esc_url( vr_page_url( 'terms-conditions' ) ); ?>">Terms &amp; conditions</a></div>
        <div><h2>Stay in the loop</h2><a href="<?php echo esc_url( vr_setting( 'instagram' ) ); ?>" target="_blank" rel="noopener">Instagram ↗</a><a href="<?php echo esc_url( vr_setting( 'facebook' ) ); ?>" target="_blank" rel="noopener">Facebook ↗</a><h2 class="vr-footer-contact">Contact us</h2><a href="mailto:<?php echo esc_attr( vr_setting( 'email' ) ); ?>"><?php echo esc_html( vr_setting( 'email' ) ); ?></a><a href="tel:<?php echo esc_attr( vr_setting( 'phone' ) ); ?>"><?php echo esc_html( vr_setting( 'phone' ) ); ?></a></div>
    </div>
    <div class="vr-container vr-footer-bottom"><span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> Vyndra. All rights reserved.</span><span>Wear it your way.</span><a href="#main">Back to top ↑</a></div>
</footer>
