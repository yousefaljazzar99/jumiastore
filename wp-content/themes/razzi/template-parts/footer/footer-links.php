<?php
/**
 * Template part for displaying footer links
 *
 * @package razzi
 */

use Razzi\Helper;

if ( ! is_active_sidebar( 'footer-links' ) ) {
	return;
}

if ( ! apply_filters( 'razzi_footer_links_section', true ) ) {
	return;
}

$add_class = Helper::get_option( 'footer_links_border' ) ? 'has-divider' : '';
$add_class .= ! intval( Helper::get_option( 'mobile_footer_links' ) ) ? ' razzi-hide-on-mobile' : '';

?>

<div class="footer-links <?php echo esc_attr( $add_class ) ?>">
	<div class="footer-container <?php echo esc_attr( apply_filters( 'razzi_footer_container_class', Helper::get_option( 'footer_container' ), 'links' ) ); ?>">
		<?php dynamic_sidebar( 'footer-links' ); ?>
	</div>
</div>