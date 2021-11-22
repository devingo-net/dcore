<?php

/**
 * Singular content
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

$singleColumns = getThemeOptions('blog-single-columns', 'right-sidebar');

if ( is_rtl() ) {
	if ( $singleColumns === 'right-sidebar' ) {
		$singleColumns = 'left-sidebar';
	} else if ( $singleColumns === 'left-sidebar' ) {
		$singleColumns = 'right-sidebar';
	}
}


if ( function_exists('WC') && (is_account_page() || is_cart() || is_checkout() || (is_page() && get_page_template_slug() === 'templates/page-full-width.php')) ) {
	$singleColumns = 'full-width';
}

?>

<div class="uk-container blog-archive-container">
    <div class="uk-flex uk-flex-wrap uk-flex-wrap-around <?= $singleColumns === 'left-sidebar' ? 'uk-flex-row-reverse' : '' ?>">
        <div class="uk-width-expand archive-posts-column">
			<?php

			if ( have_posts() ) {

				while ( have_posts() ) {
					the_post();
					get_template_part('templates/single/content', get_post_type());
				}
			}
			?>
        </div>

		<?php if ( $singleColumns !== 'full-width' ) { ?>
            <div class="sidebar-column">
				<?php
				get_sidebar('blog')
				?>
            </div>
		<?php } ?>
    </div>
</div>