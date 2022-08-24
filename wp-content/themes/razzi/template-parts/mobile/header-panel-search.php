<?php
/**
 * Template file for displaying mobile panel search
 *
 * @package Razzi
 */
use Razzi\Helper;
?>

<form method="get" class="form-search mobile-panel-search" action="<?php echo esc_url( home_url( '/' ) ) ?>">
	<div class="search-wrapper">
		<input type="text" name="s" class="search-field" value="<?php echo esc_attr( get_search_query() ); ?>"
			placeholder="<?php echo esc_attr__( 'Search for items', 'razzi' ); ?>" autocomplete="off">
		<?php if ( Helper::get_option( 'header_search_type' ) == 'product' ) : ?>
			<input type="hidden" name="post_type" value="<?php echo esc_attr( Helper::get_option( 'header_search_type' ) ) ?>">
		<?php endif; ?>
		<button class="search-submit" type="submit"><?php echo \Razzi\Icon::get_svg( 'search', '', 'shop' ); ?></button>
	</div>
</form>