<?php
/**
 * Display product quickview.
 *
 * @package       Razzi
 * @version       1.0.0
 */

use Razzi\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;
$classes = wc_get_product_class( '', $product  );
if ( Helper::get_option( 'product_add_to_cart_ajax' ) ) {
	$classes[] = 'product-add-to-cart-ajax';
}

if ( get_option( 'rz_buy_now' ) == 'yes' ) {
	$classes[] = 'has-buy-now';
}

$classes[] = 'product-is-quickview';

do_action('woocommerce_before_single_product');
?>

    <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">

		<div class="entry-thumbnail">
			<?php
			/**
			 * Hook: razzi_woocommerce_product_quickview_thumbnail
			 *
			 * @hooked woocommerce_show_product_sale_flash - 5
			 * @hooked woocommerce_show_product_images - 10
			 * @hooked product_quick_view_more_info_button - 15
			 *
			 */
			do_action( 'razzi_woocommerce_product_quickview_thumbnail' );
			?>
		</div>

		<div class="summary entry-summary razzi-scrollbar">
			<?php
			/**
			 * Hook: razzi_woocommerce_product_quickview_summary
			 *
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_title - 20
			 * @hooked open_price_box_wrapper - 30
			 * @hooked woocommerce_template_single_price - 40
			 * @hooked product_availability - 50
			 * @hooked close_price_box_wrapper - 60
			 * @hooked woocommerce_template_single_excerpt - 70
			 * @hooked woocommerce_template_single_add_to_cart - 80
			 * @hooked woocommerce_template_single_meta - 90
			 *
			 */
			do_action( 'razzi_woocommerce_product_quickview_summary' );
			?>
		</div>
	</div>

<?php
