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
		<div class="search-fields">
			<input type="text" name="s" class="search-field" value="<?php echo esc_attr( get_search_query() ); ?>"
					placeholder="<?php echo esc_attr( $args['text_placeholder'] ) ?>" autocomplete="off">
			<a href="#"
				class="close-search-results"><?php echo \Razzi\Icon::get_svg( 'close' ); ?></a>
			<span class="razzi-loading"></span>
		</div>
		<?php if ( $args['search_type'] == 'product' ) : ?>
			<input type="hidden" name="post_type" value="<?php echo esc_attr( $args['search_type'] ) ?>">
		<?php endif; ?>
		<?php
		if ( $args['search_type'] == 'product' ) {
			$args_cat = array(
				'name'            => 'product_cat',
				'taxonomy'        => 'product_cat',
				'orderby'         => 'NAME',
				'hierarchical'    => 1,
				'hide_empty'      => 1,
				'echo'            => 0,
				'value_field'     => 'slug',
				'class'           => 'product-cat-dd',
				'show_option_all' => esc_html__( 'All Categories', 'razzi' ),
				'id'              => 'product-cat',
			);

			echo sprintf(
				'<div class="product-cat">' .
				'<div class="product-cat-label"><span class="label">%s</span>%s</div>' .
				'%s' .
				'</div>',
				esc_html__( 'All Categories', 'razzi' ),
				\Razzi\Icon::get_svg( 'chevron-bottom' ),
				wp_dropdown_categories( $args_cat )
			);
		}

		?>
		<button class="search-submit"
				type="submit"><?php echo \Razzi\Icon::get_svg( 'search', '', 'shop' ); ?></button>
	</form>
<?php } ?>