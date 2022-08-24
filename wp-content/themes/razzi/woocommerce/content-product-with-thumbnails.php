<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php
	/**
	 * razzi_product_with_thumbnails_woocommerce_before_loop hook.
	 *
	 */
	do_action( 'razzi_product_with_thumbnails_woocommerce_before_loop', $image_size );

	/**
	 * razzi_product_with_thumbnails_woocommerce_before_loop_summary hook.
	 *
	 */
	do_action( 'razzi_product_with_thumbnails_woocommerce_before_loop_summary' );

	/**
	 * razzi_product_with_thumbnails_woocommerce_loop_summary hook.
	 *
	 */
	do_action( 'razzi_product_with_thumbnails_woocommerce_loop_summary' );

	/**
	 * razzi_product_with_thumbnails_woocommerce_after_loop_summary hook.
	 *
	 */
	do_action( 'razzi_product_with_thumbnails_woocommerce_after_loop_summary' );

	/**
	 * razzi_product_with_thumbnails_woocommerce_after_loop hook.
	 */
	do_action( 'razzi_product_with_thumbnails_woocommerce_after_loop' );
	?>
</li>
