<?php
/**
 * Template part for displaying custom text
 *
 * @package Razzi
 */

if ( $header_custom_text = \Razzi\Helper::get_option( 'header_custom_text' ) ) {
	echo '<div class="header-custom-text">' . do_shortcode( wp_kses_post( $header_custom_text ) ) . '</div>';
}