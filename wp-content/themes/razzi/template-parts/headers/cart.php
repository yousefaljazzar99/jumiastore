<?php
/**
 * Template part for displaying the cart icon
 *
 * @package Razzi
 */

use Razzi\Helper;

if ( ! function_exists( 'WC' ) ) {
	return;
}

$cart_behaviour = Helper::get_option( 'header_cart_behaviour' );

if( function_exists('is_cart') && is_cart() ) {
	$cart_behaviour = 'link';
}

if( function_exists('is_checkout') && is_checkout() ) {
	$cart_behaviour = 'link';
}

?>

<div class="header-cart">
	<a href="<?php echo esc_url( wc_get_cart_url() ) ?>" data-toggle="<?php echo 'panel' == $cart_behaviour ? 'modal' : 'link'; ?>" data-target="cart-modal">
		<?php
			echo \Razzi\WooCommerce\Helper::get_cart_icon();
		?>
		<span class="counter cart-counter <?php echo intval( WC()->cart->get_cart_contents_count() ) == 0 ? 'hidden' : ''; ?>"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
	</a>
</div>
