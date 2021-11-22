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

if ( is_active_sidebar( 'page_sidebar' ) ) : ?>
	<div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
		<?php dynamic_sidebar( 'page_sidebar' ); ?>
	</div>
<?php endif;