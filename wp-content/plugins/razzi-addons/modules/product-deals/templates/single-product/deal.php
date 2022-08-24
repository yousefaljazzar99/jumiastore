<?php
/**
 * Single Product Deal
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/deal.php.
 *
 * @author  Drfuri
 * @package WooCommerce Deals/Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="razzi-product-deal deal">
	<?php if ( $expire ) : ?>
        <div class="deal-expire-date">
            <div class="deal-expire-text"><?php echo wp_kses_post( $expire_text ) ?></div>
            <div class="deal-expire-countdown razzi-countdown" data-expire="<?php echo esc_attr( $expire ) ?>" data-text='<?php echo wp_json_encode( $countdown_texts ); ?>'></div>
        </div>
	<?php endif; ?>

    <div class="deal-sold">
        <div class="deal-sold-text"><?php echo wp_kses_post( $sold_items_text ) ?></div>
        <div class="deal-progress">
            <div class="progress-bar">
                <div class="progress-value" style="width: <?php echo $sold / $limit * 100 ?>%"></div>
            </div>
            <div class="deal-text"><span class="amount"><span class="sold"><?php echo $sold ?></span>/<span
                            class="limit"><?php echo $limit ?></span></span> <?php echo wp_kses_post( $sold_text ) ?></div>
        </div>
    </div>
</div>
