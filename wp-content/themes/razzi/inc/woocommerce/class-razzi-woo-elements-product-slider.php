<?php
/**
 * Product Slider Loop template hooks.
 *
 * @package Razzi
 */

namespace Razzi\WooCommerce\Elements;
use Razzi\WooCommerce\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Product Slider Loop
 */
class Product_Slider {
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
		add_action( 'razzi_woo_before_product_slider_loop_item', array( $this, 'product_wrapper_open' ), 10 );

		add_action( 'razzi_woo_before_product_slider_loop_item_title', array( $this, 'product_loop_thumbnail' ), 1 );

		add_action( 'razzi_woo_product_slider_loop_item_title', array( $this, 'summary_wrapper_open' ), 1 );
		add_action( 'razzi_woo_product_slider_loop_item_title', array( Helper::instance(), 'product_loop_title' ), 5 );
		add_action( 'razzi_woo_product_slider_loop_item_title', array( $this, 'product_taxonomy' ), 10 );
		add_action( 'razzi_woo_product_slider_loop_item_title', array( $this, 'rating_count' ), 20 );
		add_action( 'razzi_woo_after_product_slider_loop_item_title', 'woocommerce_template_loop_price', 30 );

		add_action( 'razzi_woo_after_product_slider_loop_item', array( $this, 'summary_wrapper_close' ), 900 );
		add_action( 'razzi_woo_after_product_slider_loop_item', array( $this, 'product_wrapper_close' ), 900 );

		add_action( 'razzi_woo_after_product_slider_loop_item', 'woocommerce_template_loop_add_to_cart', 1000 );

	}

	/**
	 * Open product wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_wrapper_open() {
		echo '<div class="product-wrapper">';
	}

	/**
	 * Close product wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Open product summary wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function summary_wrapper_open() {
		echo '<div class="product-summary">';
	}

	/**
	 * Close product summary wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function summary_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Open product Loop buttons.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_buttons_open() {
		echo '<div class="product-loop__buttons">';
	}

	/**
	 * Close product Loop buttons.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_buttons_close() {
		echo '</div>';
	}

	/**
	 * WooCommerce template slider thumbnail
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_loop_thumbnail() {
		echo '<div class="product-thumbnail">';

			woocommerce_template_loop_product_link_open();
			woocommerce_template_loop_product_thumbnail();
			woocommerce_template_loop_product_link_close();

			do_action( 'razzi_loop_product_slider_thumbnail' );

		echo '</div>';
	}

	/**
	 * Rating count open.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_taxonomy() {
		$taxonomy = \Razzi\Helper::get_option( 'product_loop_taxonomy' );
		Helper::product_taxonomy( $taxonomy );
	}

	/**
	 * Rating count open.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function rating_count() {
		global $product;

		echo '<div class="rating-count">';
			woocommerce_template_loop_rating();
			if ( intval($product->get_review_count()) > 0 ) {
				?>
				<div class="review-count"><?php printf( _n( 'Review (%s)', 'Reviews (%s)', $product->get_review_count(), 'razzi' ), esc_html( $product->get_review_count() ) ); ?></div>
				<?php
			}
		echo '</div>';
	}
}
