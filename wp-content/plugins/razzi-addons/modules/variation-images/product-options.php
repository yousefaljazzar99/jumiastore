<?php

namespace Razzi\Addons\Modules\Variation_Images;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class of plugin for admin
 */
class Product_Options  {

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
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 50 );

		// Linked Products tab
		add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'variation_images_html' ), 10, 3 );
		// Save product meta
		add_action( 'woocommerce_save_product_variation', array( $this, 'save_product_variation_images' ) );
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
			wp_enqueue_script( 'razzi_variation_images', RAZZI_ADDONS_URL . '/modules/variation-images/js/variation-images-admin.js', array( 'jquery' ), '20220319', true );
			wp_enqueue_style( 'razzi_variation_images', RAZZI_ADDONS_URL . '/modules/variation-images/css/variation-images-admin.css', array(), '20220319');
		}
	}

	/**
	 * Add more options to advanced tab.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function variation_images_html($loop, $variation_data, $variation) {
		$variation_id   = absint( $variation->ID );
		$gallery_images = get_post_meta( $variation_id, 'razzi_variation_images', true );
		$attachments = $gallery_images ? explode(',', $gallery_images) : '';
		?>
		<div class="form-row form-row-full razzi-variation-images-container">
			<h4><?php esc_html_e( 'Variation Images', 'razzi' ); ?></h2>
			<ul class="variation-images-list">
				<?php
				if ( ! empty( $attachments ) ) {
					foreach ( $attachments as $attachment_id ) {
						$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );
						if ( empty( $attachment ) ) {
							continue;
						}
						?>
						<li class="image" data-attachment_id="<?php echo esc_attr( $attachment_id ); ?>">
							<?php echo $attachment; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<a href="#" class="delete tips" data-tip="<?php esc_attr_e( 'Delete image', 'razzi' ); ?>"></a>
						</li>
						<?php
					}

				}
				?>
			</ul>
			<p class="hide-if-no-js">
				<a href="#" class="razzi-variation-images-upload" data-choose="<?php esc_attr_e( 'Add images to variation gallery', 'razzi' ); ?>" data-update="<?php esc_attr_e( 'Add to gallery', 'razzi' ); ?>" data-delete="<?php esc_attr_e( 'Delete image', 'razzi' ); ?>" data-text="<?php esc_attr_e( 'Delete', 'razzi' ); ?>"><?php esc_html_e( 'Add variation images gallery', 'razzi' ); ?></a>
			</p>
			<input type="hidden" class="razzi_variation_images" name="razzi_variation_images[<?php echo esc_attr( $variation->ID ); ?>]" value="<?php echo esc_attr($gallery_images ); ?>" />
		</div>
	<?php
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
	public function save_product_variation_images( $variation_id ) {
		if ( isset( $_POST['razzi_variation_images'][$variation_id ] ) ) {
			$woo_data = $_POST['razzi_variation_images'][$variation_id ];
			update_post_meta( $variation_id, 'razzi_variation_images', $woo_data );
		} else {
			delete_post_meta( $variation_id, 'razzi_variation_images' );
		}

	}

}