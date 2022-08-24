<?php

namespace Razzi\Addons\Modules\Product_Bought_Together;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class of plugin for admin
 */
class Product_Meta  {

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

	const POST_TYPE     = 'razzi_product_tab';
	const TAXONOMY_TYPE   = 'razzi_product_tab_type';

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'product_data_tab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'product_data_panel' ) );

		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
	}

	/**
	 * Add new product data tab for size guide
	 *
	 * @param array $tabs
	 *
	 * @return array
	 */
	public function product_data_tab( $tabs ) {
		$tabs['razzi_product_bought_together'] = array(
			'label'    => esc_html__( 'Frequently Bought Together', 'razzi' ),
			'target'   => 'razzi-product-bought-together',
			'priority' => 62,
			'class'  => array( 'hide_if_grouped', 'hide_if_external', 'hide_if_bundle' ),
		);

		return $tabs;
	}

	/**
	 * Outputs the size guide panel
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function product_data_panel() {
		global $post;
		$post_id = $post->ID;
		?>
		<div id="razzi-product-bought-together" class="panel wc-metaboxes-wrapper woocommerce_options_panel">
			<p class="form-field">
					<label for="related_products"><?php esc_html_e( 'Products', 'razzi' ); ?></label>
					<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="related_products" name="razzi_bought_together_product_ids[]" data-sortable="true" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'razzi' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post_id ); ?>">
						<?php
						$product_ids = maybe_unserialize( get_post_meta( $post_id, 'razzi_bought_together_product_ids', true ) );

						if ( $product_ids && is_array( $product_ids ) ) {
							foreach ( $product_ids as $product_id ) {
								$product = wc_get_product( $product_id );
								if ( is_object( $product ) ) {
									echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
								}
							}
						}
						?>
					</select> <?php echo wc_help_tip( __( 'This lets you choose which products are part of frequently bought together products.', 'razzi' ) ); // WPCS: XSS ok. ?>
				</p>
		</div>
		<?php
	}


	/**
	 * Save meta box content.
     *
	 * @since 1.0.0
	 *
	 * @param int $post_id
	 * @param object $post
     *
	 * @return void
	 */
	public function save_post( $post_id, $post ) {
		//If not the flex post.
		if ( 'product' != $post->post_type ) {
			return;
		}

		// Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
		}

		// Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
		}

		if ( isset( $_POST['razzi_bought_together_product_ids'] ) ) {
			$woo_data = $_POST['razzi_bought_together_product_ids'];
			update_post_meta( $post_id, 'razzi_bought_together_product_ids', $woo_data );
		} else {
			update_post_meta( $post_id, 'razzi_bought_together_product_ids', 0 );
		}


	}

}