<?php
/**
 * The template file for displaying the comments and comment form
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

/**
 * @var $comments
 */

use DCore\Walker\Blog_Comment_Walker;

if ( post_password_required() ) {
	return;
}

if ( $comments ) {
	?>

    <div class="comments" id="comments">

		<?php
		$comments_number = absint(get_comments_number());
		?>

        <div class="comments-header section-inner small max-percentage">
            <h2 class="uk-heading-line uk-text-center"><span>
				<?php
				if ( !have_comments() ) {
					_e('Leave a comment', THEME_TEXTDOMAIN);
				} elseif ( 1 === $comments_number ) {
					printf(_x('One reply on &ldquo;%s&rdquo;', 'comments title', THEME_TEXTDOMAIN), get_the_title());
				} else {
					printf(_nx('%1$s reply on &ldquo;%2$s&rdquo;', '%1$s replies on &ldquo;%2$s&rdquo;',
						$comments_number, 'comments title', THEME_TEXTDOMAIN), number_format_i18n($comments_number),
						get_the_title());
				}

				?>
                </span></h2>

        </div>

        <div class="comments-inner section-inner thin max-percentage">

			<?php
			wp_list_comments([
				'walker'      => new Blog_Comment_Walker(),
				'avatar_size' => 80,
				'style'       => 'div',
			]);

			$comment_pagination = paginate_comments_links([
				'echo'      => false,
				'end_size'  => 0,
				'mid_size'  => 0,
				'next_text' => __('Newer Comments', 'twentytwenty') . ' <span aria-hidden="true">&rarr;</span>',
				'prev_text' => '<span aria-hidden="true">&larr;</span> ' . __('Older Comments', THEME_TEXTDOMAIN),
			]);

			if ( $comment_pagination ) {
				$pagination_classes = '';

				if ( false === strpos($comment_pagination, 'prev page-numbers') ) {
					$pagination_classes = ' only-next';
				}
				?>

                <nav class="comments-pagination pagination<?php echo $pagination_classes; ?>"
                     aria-label="<?php esc_attr_e('Comments', THEME_TEXTDOMAIN); ?>">
					<?php echo wp_kses_post($comment_pagination); ?>
                </nav>

				<?php
			}
			?>

        </div>

    </div>
	<?php
}

if ( comments_open() || pings_open() ) {

	if ( $comments ) {
		echo '<hr class="styled-separator is-style-wide" aria-hidden="true" />';
	}

	comment_form([
		'class_form'         => 'section-inner thin max-percentage',
		'title_reply_before' => '<h2 id="reply-title" class="uk-heading-line comment-reply-title"><span>',
		'title_reply_after'  => '</span></h2>',
	]);

} elseif ( is_single() ) {

	if ( $comments ) {
		echo '<hr class="styled-separator is-style-wide" aria-hidden="true" />';
	}

	?>

    <div class="comment-respond" id="respond">

        <p class="comments-closed"><?php _e('Comments are closed.', 'twentytwenty'); ?></p>

    </div>

	<?php
}
