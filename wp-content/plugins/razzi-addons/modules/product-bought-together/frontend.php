<?php

namespace Razzi\Addons\Modules\Product_Bought_Together;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class of plugin for admin
 */
class Frontend {

	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Has variation images
	 *
	 * @var $has_variation_images
	 */
	protected static $has_variation_images = null;


	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'product_bought_together' ), 5 );

		add_action( 'wp_loaded', array( $this, 'add_to_cart_action' ), 20 );
	}

		/**
	 * Enqueue scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'razzi-product-bought-together', RAZZI_ADDONS_URL . 'modules/product-bought-together/assets/product-bought-together.css', array(), '1.0.0' );
		wp_enqueue_script('razzi-product-bought-together', RAZZI_ADDONS_URL . 'modules/product-bought-together/assets/product-bought-together.js',  array('jquery'), '1.0.0' );

		if ( is_singular( 'product' ) ) {
			$razzi_data = array(
				'currency_pos'    => get_option( 'woocommerce_currency_pos' ),
				'currency_symbol' => function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '',
				'thousand_sep'    => function_exists( 'wc_get_price_thousand_separator' ) ? wc_get_price_thousand_separator() : '',
				'decimal_sep'     => function_exists( 'wc_get_price_decimal_separator' ) ? wc_get_price_decimal_separator() : '',
				'price_decimals'  => function_exists( 'wc_get_price_decimals' ) ? wc_get_price_decimals() : '',
			);

			wp_localize_script(
				'razzi-product-bought-together', 'razziFbt', $razzi_data
			);
		}
	}

	/**
	 * Get product bought together
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_bought_together() {
		global $product;
		$product_ids = maybe_unserialize( get_post_meta( $product->get_id(), 'razzi_bought_together_product_ids', true ) );
		$product_ids = apply_filters( 'martfury_pbt_product_ids', $product_ids, $product );
		if ( empty( $product_ids ) || ! is_array( $product_ids ) ) {
			return;
		}

		$current_product = array( $product->get_id() );
		$product_ids     = array_merge( $current_product, $product_ids );

		 wc_get_template(
			'single-product/product-bought-together.php',
			array(
				'product_ids'      => $product_ids,
			),
			'',
			RAZZI_ADDONS_DIR . 'modules/product-bought-together/templates/'
		);
	}

	/**
	 * Add to cart product bought together
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function add_to_cart_action() {
		if ( empty( $_REQUEST['razzi_fbt_add_to_cart'] ) ) {
			return;
		}

		wc_nocache_headers();

		$product_ids = $_REQUEST['razzi_fbt_add_to_cart'];
		$product_ids = explode( ',', $product_ids );
		if ( ! is_array( $product_ids ) ) {
			return;
		}

		foreach ( $product_ids as $product_id ) {
			if ( $product_id == 0 ) {
				continue;
			}
			$was_added_to_cart = false;
			$adding_to_cart    = wc_get_product( $product_id );

			if ( ! $adding_to_cart ) {
				return;
			}

			$quantity          = 1;
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

			if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity ) ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
				$was_added_to_cart = true;
			}

			// If we added the product to the cart we can now optionally do a redirect.
			if ( $was_added_to_cart && 0 === wc_notice_count( 'error' ) ) {
				if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
					wp_safe_redirect( wc_get_cart_url() );
					exit;
				}
			}
		}

	}

}