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

?>

<div class="header-account">
	<a class="account-icon" href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ) ?>" data-toggle="<?php echo 'panel' == Helper::get_option( 'header_account_behaviour' ) ? 'modal' : 'link'; ?>" data-target="account-modal">
		<?php echo \Razzi\Icon::get_svg('account', '', 'shop'); ?>
	</a>
	<?php if ( is_user_logged_in() ) : ?>
		<div class="account-links">
			<?php if ( has_nav_menu( 'user_logged' ) ) { ?>
			<ul>
				<li>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'user_logged',
					'container'      => false,
				) );
				?>
				</li>
				<li class="logout">
					<?php
					$account = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
					?>
					<a href="<?php echo esc_url( wp_logout_url( $account ) ) ?>"><?php esc_html_e('Logout', 'razzi'); ?></a>
				</li>
			</ul>
			<?php } else { ?>
			<ul>
				<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
					<li class="account-link--<?php echo esc_attr( $endpoint ); ?>">
						<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="underline-hover"><?php echo esc_html( $label ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php } ?>
		</div>
	<?php endif; ?>
</div>
