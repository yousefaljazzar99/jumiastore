<?php

namespace Razzi\Vendor;

/**
 * Vendor Dokan functions and definitions.
 *
 * @package Razzi
 */

class Dokan {

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
     * The constructor
     */
    function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Enqueue registration scripts
     *
     * @return void
     */
    public function enqueue_scripts() {
        if ( is_user_logged_in() ) {
            return;
        }

		if(function_exists('dokan') ) {
			dokan()->scripts->load_form_validate_script();
			wp_enqueue_script( 'dokan-vendor-registration' );
		}

    }
}
