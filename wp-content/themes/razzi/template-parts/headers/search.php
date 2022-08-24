<?php
/**
 * Template part for displaying the search icon
 *
 * @package Razzi
 */

use Razzi\Helper;

$search_class = isset($args['search_class']) ? $args['search_class'] : '';

$args['text_placeholder'] = esc_html__( 'Search for items', 'razzi' );
if ( ! empty( Helper::get_option( 'header_search_placeholder' ) ) ) {
	$args['text_placeholder'] = esc_html( Helper::get_option( 'header_search_placeholder' ) );
} else {
	if ( $args['search_type'] == 'product' ) {
		$args['text_placeholder'] = esc_html__( 'Search for items', 'razzi' );
	}
}

if( Helper::get_option( 'header_type' ) == 'custom' &&  $args['search_form_style'] == 'boxed') {
	$search_class .= ' form-skin-' . Helper::get_option( 'header_search_form_skin' );
}

?>

<div class="header-search <?php echo esc_attr( $search_class ); ?>">
	<?php if ( $args['search_style'] == 'icon' ) : ?>
        <span class="search-icon" data-toggle="modal" data-target="search-modal">
			<?php echo \Razzi\Icon::get_svg( 'search', '', 'shop' ); ?>
		</span>
	<?php elseif ( $args['search_style'] == 'form-cat' ) : ?>
		<?php get_template_part( 'template-parts/search-form/form-cat', '', $args ); ?>
	<?php else: ?>
        <?php get_template_part( 'template-parts/search-form/form-text', '', $args ); ?>
	<?php endif; ?>
	<?php if ( $args['search_style'] != 'icon' ) : ?>
		<?php \Razzi\Header::search_quicklinks(); ?>
        <div class="search-results woocommerce"></div>
	<?php endif; ?>
</div>
