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

if ( is_active_sidebar( 'blog_sidebar' ) ) : ?>
	<div id="blog-sidebar" class="blog-sidebar widget-area" role="complementary">
		<?php dynamic_sidebar( 'blog_sidebar' ); ?>
	</div>
<?php endif;