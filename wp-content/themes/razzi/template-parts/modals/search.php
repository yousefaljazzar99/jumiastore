<?php
/**
 * Template part for modal search
 *
 * @package Razzi
 */

use Razzi\Helper;

?>
<div class="modal-header">
    <h3 class="modal-title">
		<?php
		if ( ! empty( Helper::get_option( 'header_search_text' ) ) ) {
			echo esc_html( Helper::get_option( 'header_search_text' ) );
		} else {
			if ( Helper::get_option( 'header_search_type' ) == 'product' ) {
				echo esc_html__( 'Search Products', 'razzi' );
			} else {
				echo esc_html__( 'Search', 'razzi' );
			}
		}
		?>
    </h3>
    <a href="#"
       class="close-search-panel button-close"><?php echo \Razzi\Icon::get_svg( 'close' ); ?></a>
</div>
<div class="modal-content">
	<?php get_template_part( 'template-parts/search-form/form-modal' ); ?>
	<?php \Razzi\Header::search_quicklinks(); ?>
    <div class="search-results woocommerce"></div>
</div>
