<?php

/**
 * post single content
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<?php
	get_template_part('templates/single/featured-image');
	get_template_part('templates/single/entry-header');
	get_template_part('templates/single/post-meta');
	?>

    <div class="post-inner ">
		<?php
		if ( has_excerpt() ) {
			?>
            <div class="intro-text">
				<?php the_excerpt(); ?>
            </div>
			<?php
		}
		?>
        <div class="entry-content">
			<?php the_content(); ?>
        </div>
		<?php if ( has_tag() ) { ?>
            <div class="post-tags">
                <span class="screen-reader-text"><?php _e('Tags', THEME_TEXTDOMAIN); ?></span>
                <i class="far fa-tags"></i>
                <?php the_tags('', ', ', ''); ?>
            </div>
		<?php } ?>
    </div>

    <div class="section-inner">
		<?php
		wp_link_pages([
			'before'      => '<nav class="post-nav-links bg-light-background" aria-label="' . esc_attr__('Page',
					THEME_TEXTDOMAIN) . '"><span class="label">' . __('Pages:', THEME_TEXTDOMAIN) . '</span>',
			'after'       => '</nav>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		]);
		edit_post_link();
		if ( is_single() && post_type_supports(get_post_type(get_the_ID()), 'author') ) {
			get_template_part('templates/single/entry-author-bio');

		}
		?>

    </div>

	<?php

	if ( is_single() ) {
		get_template_part('templates/single/navigation');
	}

	if ( !post_password_required() && (is_single() || is_page()) && (comments_open() || get_comments_number()) ) {
		?>
        <div class="comments-wrapper section-inner">
			<?php comments_template(); ?>
        </div>
		<?php
	}
	?>

</article>
