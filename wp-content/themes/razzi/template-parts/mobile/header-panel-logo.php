<?php
/**
 * Template file for displaying mobile menu
 *
 * @package Razzi
 */

use Razzi\Helper;
?>

<div class="mobile-logo site-branding">
	<a href="<?php echo esc_url( home_url() ) ?>" class="logo logo-text">
		<?php if ( Helper::get_option( 'mobile_panel_logo' ) ) :?>
			<img class="logo-image" src="<?php echo esc_url( Helper::get_option( 'mobile_panel_logo' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		<?php else: ?>
			<span class="logo-dark"><?php echo esc_html( Helper::get_option( 'logo_text' ) ); ?></span>
		<?php endif;?>
	</a>
</div>