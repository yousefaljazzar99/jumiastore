<?php
/**
 * External product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/external.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

$newtabs = \Razzi\Helper::get_option( 'product_external_open' ) ? 'target="_blank"' : '';

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<div class="cart">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<a href="<?php echo esc_url( $product_url ); ?>" class="single_add_to_cart_button button alt" <?php echo esc_attr( $newtabs ); ?>>
	<?php echo esc_html( $button_text ); ?>
	<?php
		echo \Razzi\WooCommerce\Helper::get_cart_icon();
	?>
	</a>

	<?php wc_query_string_form_fields( $product_url ); ?>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

</div>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
