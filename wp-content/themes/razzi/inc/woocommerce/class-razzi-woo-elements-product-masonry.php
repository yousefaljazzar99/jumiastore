<?php
/**
 * Product Loop template hooks.
 *
 * @package Razzi
 */

namespace Razzi\WooCommerce\Elements;

use Razzi\WooCommerce\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Product Loop
 */
class Product_Masonry {
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
		add_action( 'razzi_masonry_woocommerce_before_shop_loop_item', array( $this, 'product_wrapper_open' ), 10 );
		add_action( 'razzi_masonry_woocommerce_after_shop_loop_item', array(
			$this,
			'product_wrapper_close'
		), 1000 );

		add_action( 'razzi_masonry_woocommerce_before_shop_loop_item_title', array(
			$this,
			'product_loop_thumbnail'
		), 1 );

		// Group elements bellow product thumbnail.
		add_action( 'razzi_masonry_woocommerce_shop_loop_item_title', array( $this, 'summary_wrapper_open' ), 1 );
		add_action( 'razzi_masonry_woocommerce_after_shop_loop_item', array(
			$this,
			'link_product_type'
		), 999 );
		add_action( 'razzi_masonry_woocommerce_after_shop_loop_item', array(
			$this,
			'summary_wrapper_close'
		), 1000 );

		// Change the product title.
		add_action( 'razzi_masonry_woocommerce_shop_loop_item_title', array( Helper::instance(), 'product_loop_title' ) );

		// Remove rating
		add_action( 'razzi_masonry_woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 );

		// Add Price
		if ( apply_filters( 'razzi_product_loop_show_price', true ) ) {
			add_action( 'razzi_masonry_woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 20 );
		}

		add_action( 'razzi_masonry_woocommerce_shop_loop_item_title', array( Helper::instance(), 'product_taxonomy' ), 5 );

		add_action( 'razzi_masonry_woocommerce_after_shop_loop_item', array(
			$this,
			'product_loop_buttons_open'
		), 5 );

		if ( apply_filters( 'razzi_product_loop_show_atc', true ) ) {
			add_action( 'razzi_masonry_woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		}

		add_action( 'razzi_masonry_woocommerce_after_shop_loop_item', array( Helper::instance(), 'quick_view_button' ), 15 );

		add_action( 'razzi_masonry_woocommerce_after_shop_loop_item', array( Helper::instance(), 'wishlist_button' ), 20 );

		if ( apply_filters( 'razzi_product_loop_show_atc', true ) ) {
			add_action( 'razzi_masonry_woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 50 );
		}

		add_action( 'razzi_masonry_woocommerce_after_shop_loop_item', array(
			$this,
			'product_loop_buttons_close'
		), 30 );
	}

	/**
	 * Open product wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_wrapper_open() {
		echo '<div class="product-inner">';
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
	 * Link product.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function link_product_type() {
		echo '<a class="link-product-all" href="'.get_permalink().'"></a>';
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
	 * WooCommerce template showcase thumbnail
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

		do_action( 'razzi_product_loop_masonry_thumbnail' );

		echo '</div>';
	}

}
