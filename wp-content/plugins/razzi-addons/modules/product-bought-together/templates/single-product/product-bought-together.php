<?php
/**
 * Product Bought Together
 *
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>

<div class="razzi-product-fbt" id="razzi-product-fbt">
	<h3 class="razzi-product-fbt__title"><?php esc_html_e( 'Frequently Bought Together', 'razzi' ); ?></h3>
	<ul class="products">
		<?php
		$total_price = 0;
		$product_titles = '';
		foreach ( $product_ids as $product_id ) {
			$product_id = apply_filters( 'wpml_object_id', $product_id, 'product' );
			$item       = wc_get_product( $product_id );

			if ( empty( $item ) ) {
				continue;
			}

			if ( $item->is_type( 'variable' ) ) {
				$key = array_search( $product_id, $product_ids );
				if ( $key !== false ) {
					unset( $product_ids[ $key ] );
				}
				continue;
			}

			if ( $item->get_stock_status() == 'outofstock' ) {
				$key = array_search( $product_id, $product_ids );
				if ( $key !== false ) {
					unset( $product_ids[ $key ] );
				}
				continue;
			}

			$data_id = $item->get_id();
			if ( $item->get_parent_id() > 0 ) {
				$data_id = $item->get_parent_id();
			}
			$total_price  += wc_get_price_to_display( $item );
			$current_item = '';
			$current_class = '';
			if ( $item->get_id() == $product->get_id() ) {
				$current_item = sprintf( '<strong>%s</strong>', esc_html__( 'This item:', 'razzi' ) );
				$current_class = 'product-current';
			}

			$product_name = $item->get_name();
			if( $product_titles ) {
				$product_titles .= ', ';
			}
			$product_titles .= $product_name;
			$product_list[] = sprintf(
				'<li class="%s"><a href="%s" data-id="%s" data-title="%s"><span class="p-title">%s %s</span></a><span class="s-price" data-price="%s">(%s)</span></li>',
				esc_attr($current_class),
				esc_url( $item->get_permalink() ),
				esc_attr( $item->get_id() ),
				esc_attr( $product_name ),
				$current_item,
				$product_name,
				esc_attr( $item->get_price() ),
				$item->get_price_html()
			);
			?>
			<li class="product" data-id="<?php echo esc_attr( $data_id ); ?>"
				id="fbt-product-<?php echo esc_attr( $item->get_id() ); ?>">
				<div class="product-content">
					<a class="thumbnail" href="<?php echo esc_url( $item->get_permalink() ) ?>">
						<?php echo wp_get_attachment_image( $item->get_image_id(), 'shop_catalog' ); ?>
					</a>
					<h2>
						<a href="<?php echo esc_url( $item->get_permalink() ) ?>">
							<?php echo esc_html( $product_name ); ?>
						</a>
					</h2>

					<div class="price">
						<?php echo wp_kses_post( $item->get_price_html() ); ?>
					</div>
				</div>
				<?php ?>
			</li>
			<?php
		}
		?>
		<li class="product product-buttons">
			<div class="price-box">
				<span class="label"><?php esc_html_e( 'Total Price: ', 'razzi' ); ?></span>
				<span class="s-price razzi-fbt-total-price"><?php echo wc_price( $total_price ); ?></span>
				<input type="hidden" data-price="<?php echo esc_attr( $total_price ); ?>" id="razzi-data_price">
			</div>
			<?php
			$product_ids = implode( ',', $product_ids );
			?>
			<form class="fbt-cart cart" action="<?php echo esc_url( $product->get_permalink() ); ?>" method="post"
					enctype="multipart/form-data">
				<input class="rz_product_id" type="hidden" data-title="<?php echo esc_attr( $product_titles ) ?>" value="<?php echo esc_attr( $product->get_id() ) ?>">
				<button type="submit" name="razzi_fbt_add_to_cart" value="<?php echo esc_attr( $product_ids ); ?>"
						class="razzi-fbt-add-to-cart ajax_add_to_cart"><?php esc_html_e( 'Add All To Cart', 'razzi' ); ?></button>
			</form>
		</li>
	</ul>
	<div class="clear"></div>
	<ul class="products-list">
		<?php echo implode( '', $product_list ); ?>
	</ul>
</div>