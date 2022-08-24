<?php
/**
 * Mobile functions and definitions.
 *
 * @package Razzi
 */

namespace Razzi\Mobile;

use Razzi\Helper;
use WeDevs\WeMail\Rest\Help\Help;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Mobile initial
 *
 */
class Catalog {
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
		add_action( 'wp', array( $this, 'hooks' ), 0 );
	}

	/**
	 * Hooks
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		if ( ! \Razzi\Helper::is_catalog() ) {
			return;
		}

		add_filter( 'body_class', array( $this, 'body_classes' ) );

		add_filter('razzi_get_catalog_layout', array( $this, 'get_catalog_layout' ));

		add_filter('razzi_get_catalog_toolbar_layout', array( $this, 'get_catalog_toolbar_layout' ));

		add_filter('razzi_get_catalog_localize_data', array( $this, 'get_catalog_localize_data' ));

		// Remove page header class
		add_filter( 'razzi_page_header_class', '__return_empty_string' );

		add_filter('razzi_get_page_header', '__return_false');

		if( Helper::get_option('mobile_catalog_page_header_layout') == 'layout-1' ) {
			add_filter('razzi_get_header_background', '__return_false');
		}

		// remove sidebar
		add_filter( 'razzi_get_sidebar', '__return_false' );

		add_action( 'woocommerce_before_shop_loop', array( $this, 'page_header' ), 10 );

		// remove toolbar mobile
		remove_action( 'woocommerce_before_shop_loop', array(
			\Razzi\Theme::instance()->get( 'woocommerce' )->get_template( 'catalog' ),
			'products_toolbar'
		), 40 );
		add_action( 'woocommerce_before_shop_loop', array( $this, 'products_toolbar' ), 20 );

		add_action( 'razzi_get_product_filters_modal_sidebar', array( $this, 'get_product_filters_modal_sidebar' ) );
		add_action( 'razzi_get_product_filters_modal_classes', array( $this, 'get_product_filters_modal_classes' ) );
		add_filter( 'razzi_get_catalog_sidebar_before_content', '__return_false' );

		// remove banners
		remove_action( 'woocommerce_before_shop_loop', array(
			\Razzi\Theme::instance()->get( 'woocommerce' )->get_template( 'catalog' ),
			'products_banners_top'
		), 10 );

		if( ! Helper::get_option('mobile_top_categories_shop_page') ) {
			// remove top categories
			remove_action( 'woocommerce_before_shop_loop', array(
				\Razzi\Theme::instance()->get( 'woocommerce' )->get_template( 'catalog' ),
				'products_top_categories'
			), 20 );
		}

	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	public function body_classes( $classes ) {

		if( Helper::get_option( 'mobile_catalog_page_header_layout' ) == 'layout-2' ) {
			$classes[] = 'razzi-catalog-page-content__no-top-spacing';
		}

		return $classes;
	}

	/**
	 * Catalog layout
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_catalog_layout() {
		return 'grid';
	}

	/**
	 * Catalog layout
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_catalog_toolbar_layout() {
		return 'v0';
	}

	/**
	 * Get catalog localize data
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_catalog_localize_data($razzi_catalog_data) {
		$razzi_catalog_data['catalog_filters_sidebar_collapse_content'] = 1;

		return $razzi_catalog_data;
	}

	/**
	 * Catalog products toolbar.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function products_toolbar() {
		if ( wc_get_loop_prop( 'is_shortcode' ) ) {
			return;
		}

		$navigation_bar = Helper::get_option( 'mobile_navigation_bar' );
		if ( in_array( $navigation_bar, array( 'simple_adoptive', 'standard_adoptive' ) ) ) {
		    return;
		}
		?>
        <div class="catalog-toolbar">
			<div class="product-toolbar-header clearfix">
				<?php
				Helper::posts_found();
				?>
        	</div>
			<?php $this->products_toolbar_filter(); ?>
        </div>

		<?php
	}

	/**
	 * Catalog Page Header.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function page_header() {
		if ( ! intval(Helper::get_option( 'mobile_catalog_page_header' ) )) {
			return;
		}

		$layout = Helper::get_option( 'mobile_catalog_page_header_layout' );
		if( $layout != 'template' ) {
			?>
				<div class="mobile-catalog-page-header mobile-catalog-page-header--<?php echo esc_attr($layout); ?>">
					<?php
						if ( $layout== 'layout-2' && ! empty( Helper::get_option( 'mobile_catalog_page_header_image' ) ) ) {
							echo '<div class="featured-image" style="background-image: url(' . esc_url( Helper::get_option( 'mobile_catalog_page_header_image' ) ) . ')"></div>';
						}
					?>
					<div class="page-header__content">
						<?php
							$items = (array) Helper::get_option( 'mobile_catalog_page_header_els' );
							if( in_array( 'breadcrumb', $items )) {
								\Razzi\Theme::instance()->get( 'breadcrumbs' )->breadcrumbs();
							}
							if( in_array( 'title', $items )) {
								the_archive_title('<h1 class="page-header__title">', '</h1>');
							}
						?>
					</div>
				</div>
			<?php
		} else {
			$template_id = Helper::get_option('mobile_shop_header_template_id');
			if ( class_exists( 'Elementor\Plugin' ) ) {
				$elementor_instance = \Elementor\Plugin::instance();
				echo !empty($elementor_instance) ? $elementor_instance->frontend->get_builder_content_for_display( $template_id ) : '';
			}

		}
		?>
		<?php
	}


	/**
	 * Catalog products toolbar.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function products_toolbar_filter() {
		if ( wc_get_loop_prop( 'is_shortcode' ) ) {
			return;
		}

		\Razzi\Theme::instance()->set_prop( 'modals', 'filter' );
		?>
        <a href="#catalog-filters" class="toggle-filters catalog-toolbar-item__filter"
           data-toggle="modal"
           data-target="catalog-filters-modal">
            <?php echo \Razzi\Icon::get_svg( 'filter-2', '', 'mobile' ); ?>
		   	<?php echo ! empty( Helper::get_option( 'mobile_filter_label' ) ) ? '<span class="catalog-toolbar-item__filter--label">' . esc_html( Helper::get_option( 'mobile_filter_label' ) ) . '</span>' : ''; ?>
        </a>
		<?php
	}

	/**
	 * Change to mobile sidebar filter
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_product_filters_modal_sidebar( $sidebar ) {
		if ( is_active_sidebar( 'catalog-filters-mobile' ) ) {
			$sidebar = 'catalog-filters-mobile';
		} elseif ( is_active_sidebar( 'catalog-filters-sidebar' ) ) {
			$sidebar = 'catalog-filters-sidebar';
		} elseif ( is_active_sidebar( 'catalog-sidebar' ) ) {
			$sidebar = 'catalog-sidebar';
		}

		return $sidebar;
	}

	/**
	 * Change class filter status
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_product_filters_modal_classes( ) {
		return 'has-collapse-hide';
	}
}
