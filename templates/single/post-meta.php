<?php

/**
 *  Post meta
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

if(is_page()){
    return;
}

?>

<div class="post-meta-wrapper">
    <ul class="post-meta">
		<?php if ( post_type_supports(get_post_type(), 'author') ) { ?>
            <li class="post-author meta-wrapper">
            <span class="meta-icon">
                <span class="screen-reader-text"><?php _e('Post author', THEME_TEXTDOMAIN); ?></span>
                <i class="far fa-user"></i>
            </span>
                <span class="meta-text">
                <?php
                printf(__('By %s', THEME_TEXTDOMAIN),
	                '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author_meta('display_name')) . '</a>');
                ?>
            </span>
            </li>
		<?php } ?>
        <li class="post-date meta-wrapper">
            <span class="meta-icon">
                <span class="screen-reader-text"><?php _e('Post date', THEME_TEXTDOMAIN); ?></span>
                <i class="far fa-calendar-note"></i>
            </span>
            <span class="meta-text">
                <a href="<?php the_permalink(); ?>"><?php the_time(get_option('date_format')); ?></a>
            </span>
        </li>
		<?php if ( has_category() ) { ?>
            <li class="post-categories meta-wrapper">
                    <span class="meta-icon">
                        <span class="screen-reader-text"><?php _e('Categories', THEME_TEXTDOMAIN); ?></span>
                        <i class="far fa-folder"></i>
                    </span>
                <span class="meta-text">
                        <?php _ex('In', 'A string that is output before one or more categories',
	                        THEME_TEXTDOMAIN); ?><?php the_category(', '); ?>
                    </span>
            </li>
		<?php } ?>

		<?php if ( !post_password_required() && (comments_open() || get_comments_number()) ) { ?>
            <li class="post-comment-link meta-wrapper">
                <span class="meta-icon"><i class="far fa-comments"></i></span>
                <span class="meta-text">
                <?php comments_popup_link(); ?>
            </span>
            </li>
		<?php } ?>

		<?php if ( is_sticky() ) { ?>
            <li class="post-sticky meta-wrapper">
                <span class="meta-icon"><i class="far fa-bookmark"></i></span>
                <span class="meta-text">
                <?php _e('Sticky post', THEME_TEXTDOMAIN); ?>
            </span>
            </li>
		<?php } ?>
    </ul>
</div>