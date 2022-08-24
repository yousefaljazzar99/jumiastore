<?php
/**
 * Login Popup template hooks.
 *
 * @package Razzi
 */

namespace Razzi\WooCommerce\Modules;
use Razzi\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Login Popup template.
 */
class Login_AJAX {
	/**
	 * Instance
	 *
	 * @var $instance
	 */
	protected static $instance = null;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_footer', array( $this, 'account_modal' ) );
		// Authenticate a user, confirming the login credentials are valid.
		add_action( 'wc_ajax_razzi_login_authenticate', array( $this, 'login_authenticate' ) );
	}

	/**
	 * Display Account Modal
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function account_modal() {
		$modals = \Razzi\Theme::instance()->get_prop( 'modals' );

		if ( ! in_array( 'account', $modals ) ) {
			return;
		}

		if ( is_user_logged_in() ) {
			return;
		}

		if( function_exists('is_account_page') && is_account_page() ) {
			return;
		}

		if ( Helper::get_option( 'header_account_behaviour' ) == 'link' ) {
			return;
		}
		?>
        <div id="account-modal" class="account-modal rz-modal ra-account-modal" tabindex="-1" role="dialog">
            <div class="off-modal-layer"></div>
            <div class="account-panel-content panel-content">
				<?php get_template_part( 'template-parts/modals/account' ); ?>
            </div>
        </div>
		<?php

	}

	/**
	 * Authenticate a user, confirming the login credentials are valid.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function login_authenticate() {
		check_ajax_referer( 'woocommerce-login', 'security' );

		$creds = array(
			'user_login'    => trim( wp_unslash( $_POST['username'] ) ),
			'user_password' => $_POST['password'],
			'remember'      => isset( $_POST['rememberme'] ),
		);

		// Apply WooCommerce filters
		if ( class_exists( 'WooCommerce' ) ) {
			$validation_error = new \WP_Error();
			$validation_error = apply_filters( 'woocommerce_process_login_errors', $validation_error, $creds['user_login'], $creds['user_password'] );

			if ( $validation_error->get_error_code() ) {
				wp_send_json_error( $validation_error->get_error_message() );
			}

			if ( empty( $creds['user_login'] ) ) {
				wp_send_json_error( esc_html__( 'Username is required.', 'razzi' ) );
			}

			// On multisite, ensure user exists on current site, if not add them before allowing login.
			if ( is_multisite() ) {
				$user_data = get_user_by( is_email( $creds['user_login'] ) ? 'email' : 'login', $creds['user_login'] );

				if ( $user_data && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
					add_user_to_blog( get_current_blog_id(), $user_data->ID, 'customer' );
				}
			}

			$creds = apply_filters( 'woocommerce_login_credentials', $creds );
		}

		$user = wp_signon( $creds, is_ssl() );

		if ( is_wp_error( $user ) ) {
			wp_send_json_error( $user->get_error_message() );
		} else {
			wp_send_json_success( $user );
		}
	}

}
