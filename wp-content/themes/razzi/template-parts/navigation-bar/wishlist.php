<?php
/**
 * Template file for displaying wishlist mobile
 *
 * @package Razzi
 */
use Razzi\Helper;

if ( ! function_exists( 'WC' ) ) {
	return;
}

if (! defined( 'YITH_WCWL' ) ) {
	return;
}

$modal_class = is_user_logged_in() ? 'link' : 'modal';
?>

<a href="<?php echo esc_url( get_permalink( yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) ) ) ) ?>" class="rz-navigation-bar_icon wishlist-icon">
	<?php echo \Razzi\Icon::get_svg( 'heart', '', 'shop' ); ?>
	<?php if( intval( Helper::get_option( 'mobile_navigation_bar_wishlist_counter' ) ) ) : ?>
		<span class="counter wishlist-counter <?php echo intval( YITH_WCWL()->count_products() ) == 0 ? 'hidden' : ''; ?>"><?php echo intval( YITH_WCWL()->count_products() ); ?></span>
	<?php endif; ?>
</a>
