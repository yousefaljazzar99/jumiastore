<?php

/**
 * WooCommerce Product additional settings.
 *
 * @package Razzi
 */

namespace Razzi\WooCommerce\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class of Product Settings
 */
class Product {
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
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 50 );

		// Advanced tab
		add_action( 'woocommerce_product_options_advanced', array( $this, 'product_advanced_options' ) );

		// Save product meta
		add_action( 'woocommerce_process_product_meta', array( $this, 'product_meta_fields_save' ) );

		// Product meta box.
		add_filter( 'rwmb_meta_boxes', array( $this, 'get_product_meta_boxes' ) );

		add_action( 'wp_ajax_razzi_wc_product_attributes', array( $this, 'wc_get_product_attributes' ) );
		add_action( 'wp_ajax_nopriv_razzi_wc_product_attributes', array( $this, 'wc_get_product_attributes' ) );

	}

	/**
	 * Enqueue Scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts( $hook ) {
		$screen = get_current_screen();
		if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) && $screen->post_type == 'product' ) {
			wp_enqueue_script( 'razzi_wc_settings_js', get_template_directory_uri() . '/assets/js/backend/woocommerce.js', array( 'jquery' ), '20220301', true );
			wp_localize_script(
				'razzi_wc_settings_js',
				'razzi_wc_settings',
				array(
					'search_tag_nonce'   => wp_create_nonce( 'search-tags' ),
				)
			);
			wp_enqueue_style( 'razzi_wc_settings_style', get_template_directory_uri() . "/assets/css/woocommerce-settings.css", array(), '20220301' );
		}
	}

	/**
	 * Add more options to advanced tab.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_advanced_options() {
		$post_custom = get_post_custom( get_the_ID());

		woocommerce_wp_text_input(
			array(
				'id'       => 'custom_badges_text',
				'label'    => esc_html__( 'Custom Badge Text', 'razzi' ),
				'desc_tip'    => true,
				'description' => esc_html__( 'Enter this optional to show your badges.', 'razzi' ),
			)
		);

		$bg_color = ( isset( $post_custom['custom_badges_bg'][0] ) ) ? $post_custom['custom_badges_bg'][0] : '';
		woocommerce_wp_text_input(
			array(
				'id'       => 'custom_badges_bg',
				'label'    => esc_html__( 'Custom Badge Background', 'razzi' ),
				'description' => esc_html__( 'Pick background color for your badge', 'razzi' ),
				'value'    => $bg_color,
			)
		);

		$color = ( isset( $post_custom['custom_badges_color'][0] ) ) ? $post_custom['custom_badges_color'][0] : '';
		woocommerce_wp_text_input(
			array(
				'id'       => 'custom_badges_color',
				'label'    => esc_html__( 'Custom Badge Color', 'razzi' ),
				'description' => esc_html__( 'Pick color for your badge', 'razzi' ),
				'value'    => $color,
			)
		);

		woocommerce_wp_checkbox( array(
			'id'          => '_is_new',
			'label'       => esc_html__( 'New product?', 'razzi' ),
			'description' => esc_html__( 'Enable to set this product as a new product. A "New" badge will be added to this product.', 'razzi' ),
		) );
		echo '<div class="options_group"></div>';

		echo '<div class="options_group product-attributes" id="razzi-product-attributes">';
		$this->get_product_attributes(get_the_ID());
		echo '</div>';

		if( \Razzi\WooCommerce\Helper::is_product_bg_color() ) {
			$product_color = ( isset( $post_custom['product_background_color'][0] ) ) ? $post_custom['product_background_color'][0] : '';
			woocommerce_wp_text_input(
				array(
					'id'       => 'product_background_color',
					'label'    => esc_html__( 'Product Background Color', 'razzi' ),
					'description' => esc_html__( 'Pick a background color for product page. Or leave it empty to automatically detect the background from product main image.', 'razzi' ),
					'value'    => $product_color,
				)
			);
		}

	}

	/**
	 * product_meta_fields_save function.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $post_id
	 *
	 * @return void
	 */
	public function product_meta_fields_save( $post_id ) {
		if ( isset( $_POST['custom_badges_text'] ) ) {
			$woo_data = $_POST['custom_badges_text'];
			update_post_meta( $post_id, 'custom_badges_text', $woo_data );
		}

		if ( isset( $_POST['custom_badges_bg'] ) ) {
			$woo_data = $_POST['custom_badges_bg'];
			update_post_meta( $post_id, 'custom_badges_bg', $woo_data );
		}

		if ( isset( $_POST['custom_badges_color'] ) ) {
			$woo_data = $_POST['custom_badges_color'];
			update_post_meta( $post_id, 'custom_badges_color', $woo_data );
		}

		if ( isset( $_POST['product_background_color'] ) ) {
			$woo_data = $_POST['product_background_color'];
			update_post_meta( $post_id, 'product_background_color', $woo_data );
		}

		if ( isset( $_POST['_is_new'] ) ) {
			$woo_data = $_POST['_is_new'];
			update_post_meta( $post_id, '_is_new', $woo_data );
		} else {
			update_post_meta( $post_id, '_is_new', 0 );
		}

		if ( isset( $_POST['rz_product_attribute'] ) ) {
			$woo_data = $_POST['rz_product_attribute'];
			update_post_meta( $post_id, 'rz_product_attribute', $woo_data );
		}

		if ( isset( $_POST['rz_product_attribute_number'] ) ) {
			$woo_data = intval($_POST['rz_product_attribute_number']);
			$woo_data = ! $woo_data ? '' : $woo_data;
			update_post_meta( $post_id, 'rz_product_attribute_number', $woo_data );
		}

	}

	/**
	 * Register meta boxes for product.
	 *
	 * @since 1.0.0
	 *
	 * @param array $meta_boxes The Meta Box plugin configuration variable for meta boxes.
	 *
	 * @return array
	 */
	public function get_product_meta_boxes( $meta_boxes ) {
		$video_atts = [];
		$video_atts[] =  array(
			'name' => esc_html__( 'Video URL', 'razzi' ),
			'id'   => 'video_url',
			'type' => 'oembed',
			'std'  => false,
			'desc' => esc_html__( 'Enter URL of Youtube or Vimeo or specific filetypes such as mp4, webm, ogv.', 'razzi' ),
		);

		if( \Razzi\Helper::get_option('product_play_video') == 'load' ) {
			$video_atts[] = array(
				'name'             => esc_html__( 'Video Thumbnail', 'razzi' ),
				'id'               => 'video_thumbnail',
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
				'std'              => false,
				'desc'             => esc_html__( 'Add video thumbnail', 'razzi' ),
			);

			$video_atts[] = array(
				'name' => esc_html__( 'Video Position', 'razzi' ),
				'id'   => 'video_position',
				'type' => 'number',
				'std'  => '1',
			);
		}
		$meta_boxes[] = array(
			'id'       => 'product-videos',
			'title'    => esc_html__( 'Product Video', 'razzi' ),
			'pages'    => array( 'product' ),
			'context'  => 'side',
			'priority' => 'low',
			'fields'   => $video_atts,
		);

		return $meta_boxes;
	}

	/**
	 * Get Product Attributes AJAX function.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function wc_get_product_attributes() {
		$post_id = $_POST['post_id'];

		if ( empty( $post_id ) ) {
			return;
		}
		ob_start();
		$this->get_product_attributes($post_id);
		$response = ob_get_clean();
		wp_send_json_success( $response );
		die();
	}

	/**
	 * Get Product Attributes function.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function get_product_attributes ($post_id) {
		$product_object = wc_get_product( $post_id );
		if( ! $product_object ) {
			return;
		}
		$attributes = $product_object->get_attributes();

		if( ! $attributes ) {
			return;
		}
		$options         = array();
		$options['']     = esc_html__( 'Default', 'razzi' );
		$options['none'] = esc_html__( 'None', 'razzi' );
		foreach ( $attributes as $attribute ) {
			$options[ sanitize_title( $attribute['name'] ) ] = wc_attribute_label( $attribute['name'] );
		}
		woocommerce_wp_radio(
			array(
				'id'       => 'rz_product_attribute',
				'label'    => esc_html__( 'Product Attribute', 'razzi' ),
				'desc_tip'    => true,
				'description' => esc_html__( 'Show the product attribute in the product card', 'razzi' ),
				'options'  => $options
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'       => 'rz_product_attribute_number',
				'label'    => esc_html__( 'Product Attribute Number', 'razzi' ),
				'desc_tip'    => true,
				'description' => esc_html__( 'Show number of the product attribute in the product card', 'razzi' ),
				'options'  => $options
			)
		);

	}
}
