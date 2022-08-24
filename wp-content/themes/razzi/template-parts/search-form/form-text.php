<?php
/**
 * Template part for modal search
 *
 * @package Razzi
 */

use Razzi\Helper;

?>
<?php if( Helper::get_option('header_search_form_type') == 'shortcode' ) {
	echo do_shortcode(  wp_kses_post( Helper::get_option( 'header_search_form_shortcode' ) ) );
} else { ?>
	<form method="get" class="form-search" action="<?php echo esc_url( home_url( '/' ) ) ?>">
		<input type="text" name="s" class="search-field" value="<?php echo esc_attr( get_search_query() ); ?>"
				placeholder="<?php echo esc_attr( $args['text_placeholder'] ) ?>" autocomplete="off">
		<?php if ( $args['search_type'] == 'product' ) : ?>
			<input type="hidden" name="post_type" value="<?php echo esc_attr( $args['search_type'] ) ?>">
		<?php endif; ?>
		<a href="#"
			class="close-search-results"><?php echo \Razzi\Icon::get_svg( 'close', '' ); ?></a>
		<button class="search-submit"
				type="submit"><?php echo \Razzi\Icon::get_svg( 'search', '', 'shop' ); ?></button>
		<span class="razzi-loading"></span>
	</form>
<?php } ?>