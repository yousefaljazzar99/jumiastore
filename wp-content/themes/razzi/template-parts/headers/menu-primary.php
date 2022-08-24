<?php
/**
 * Template part for display primary menu
 *
 * @package Razzi
 */

 $classes = 'main-navigation primary-navigation';
if( \Razzi\Helper::get_option('header_active_primary_menu_color') == 'current' ) {
	$classes .= ' main-menu-current-color';
}
if( \Razzi\Helper::get_option('primary_menu_show_arrow') ) {
	$classes .= ' has-arrow';
}

?>
<nav id="primary-menu" class="<?php echo esc_attr( $classes ); ?>">
	<?php \Razzi\Theme::instance()->get('header')->primary_menu(); ?>
</nav>
