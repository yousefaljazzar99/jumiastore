<?php

namespace Razzi\Addons\Modules\Variation_Images;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class of plugin for admin
 */
class Settings  {

	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;


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
		add_filter( 'woocommerce_get_sections_products', array( $this, 'variation_images_section' ), 30, 2 );
		add_filter( 'woocommerce_get_settings_products', array( $this, 'variation_images_settings' ), 30, 2 );
	}

	/**
	 * Variation Images Gallery section
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function variation_images_section( $sections ) {
		$sections['rz_variation_images'] = esc_html__( 'Variation Images Gallery', 'razzi' );

		return $sections;
	}

	/**
	 * Adds settings to product display settings
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings
	 * @param string $section
	 *
	 * @return array
	 */
	public function variation_images_settings( $settings, $section ) {
		if ( 'rz_variation_images' == $section ) {
			$settings = array();

			$settings[] = array(
				'id'    => 'rz_variation_images_options',
				'title' => esc_html__( 'Variation Images Gallery', 'razzi' ),
				'type'  => 'title',
			);

			$settings[] = array(
				'id'      => 'rz_variation_images',
				'title'   => esc_html__( 'Variation Images Gallery', 'razzi' ),
				'desc'    => esc_html__( 'Enable Variation Images Gallery', 'razzi' ),
				'type'    => 'checkbox',
				'default' => 'no',
			);

			$settings[] = array(
				'id'   => 'rz_variation_images_options',
				'type' => 'sectionend',
			);
		}

		return $settings;
	}

}