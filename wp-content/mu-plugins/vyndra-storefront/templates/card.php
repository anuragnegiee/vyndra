<?php
defined( 'ABSPATH' ) || exit;
global $product;
if ( $product && $product->is_visible() ) { vr_card( $product ); }
