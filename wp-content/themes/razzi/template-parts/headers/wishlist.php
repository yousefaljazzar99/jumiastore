<?php
/**
 * Template part for displaying the search icon
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

 $link = get_permalink( yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) ) );

 if ( Helper::get_option( 'header_wishlist_link' ) ) {
	$link = Helper::get_option( 'header_wishlist_link' );
 }

 $wislist_icon = get_option( 'yith_wcwl_add_to_wishlist_icon' );
 $wislist_icon = $wislist_icon == 'fa-heart-o' ? '' : $wislist_icon;

?>

<div class="header-wishlist">
	<a class="wishlist-icon" href="<?php echo esc_url( $link ) ?>">
		<?php echo ! empty( $wislist_icon ) ? '<i class="fa ' . esc_attr( $wislist_icon ) . '"></i>' : \Razzi\Icon::get_svg('heart', '', 'shop'); ?>
		<?php if( intval( Helper::get_option( 'header_wishlist_counter' ) ) ) : ?>
			<span class="counter wishlist-counter <?php echo intval( YITH_WCWL()->count_products() ) == 0 ? 'hidden' : ''; ?>"><?php echo intval( YITH_WCWL()->count_products() ); ?></span>
		<?php endif; ?>
	</a>
</div>
