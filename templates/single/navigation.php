<?php

/**
 * Displays the next and previous post navigation in single posts.
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

$nextPost = get_next_post();
$prevPost = get_previous_post();

if ( $nextPost || $prevPost ) {

	$paginationClasses = '';

	if ( !$nextPost ) {
		$paginationClasses = ' only-one only-prev';
	} elseif ( !$prevPost ) {
		$paginationClasses = ' only-one only-next';
	}

	?>
    <nav class="pagination-single uk-margin-medium-bottom section-inner<?php echo esc_attr($paginationClasses); ?>"
         aria-label="<?php
	     esc_attr_e('Post', THEME_TEXTDOMAIN); ?>" role="navigation">


        <div class="pagination-single-inner uk-flex">

			<?php
			if ( $prevPost ) {
				?>
                <div class="uk-flex-1">
                    <a class="previous-post" href="<?php echo esc_url(get_permalink($prevPost->ID)); ?>">
                        <span class="arrow" aria-hidden="true"><i class="far fa-angle-left"></i></span>
                        <span class="title"><span
                                    class="title-inner"><?php echo wp_kses_post(get_the_title($prevPost->ID)); ?></span></span>
                    </a>
                </div>
				<?php
			}

			if ( $nextPost ) {
				?>
                <div>
                    <a class="next-post" href="<?php echo esc_url(get_permalink($nextPost->ID)); ?>">
                        <span class="title"><span
                                    class="title-inner"><?php echo wp_kses_post(get_the_title($nextPost->ID)); ?></span></span>
                        <span class="arrow" aria-hidden="true"><i class="far fa-angle-right"></i></span>
                    </a>
                </div>
				<?php
			}
			?>

        </div>


    </nav>

	<?php
}

