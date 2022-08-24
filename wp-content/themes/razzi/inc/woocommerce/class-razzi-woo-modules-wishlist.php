<?php
/**
 * Mini Cart template hooks.
 *
 * @package Razzi
 */

namespace Razzi\WooCommerce\Modules;

use Razzi\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Mini Cart template.
 */
class Wishlist {
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
		// Ajax update mini cart.
		add_action( 'wc_ajax_update_wishlist_item', array( $this, 'update_wishlist_item' ) );
	}

	/**
	 * Update a single cart item.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function update_wishlist_item() {
		if ( ! function_exists( 'YITH_WCWL' ) ) {
			return;
		}

		wp_send_json( YITH_WCWL()->count_products() );
	}

}
