<?php

/**
 *
 *
 * @category
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	exit;
}
$shopColumns = getShopOptions('shop-archive-columns', 'right-sidebar');

if ( $shopColumns === 'full-width' ) {
	return;
}

if ( is_active_sidebar('shop_sidebar') ) : ?>
    <div id="shop-sidebar" class="sidebar-column shop-sidebar widget-area" role="complementary">
		<?php dynamic_sidebar('shop_sidebar'); ?>
    </div>
<?php endif;