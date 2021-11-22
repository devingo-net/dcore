<?php

/**
 * comment item template
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

/**
 * @var WP_Comment $comment Comment to display.
 * @var int        $depth   Depth of the current comment.
 * @var array      $args    An array of arguments.
 */


$commenter        = wp_get_current_commenter();
$showPendingLinks = !empty($commenter['comment_author']);

if ( $commenter['comment_author_email'] ) {
	$moderationNote = __('Your comment is awaiting moderation.', THEME_TEXTDOMAIN);
} else {
	$moderationNote = __('Your comment is awaiting moderation. This is a preview, your comment will be visible after it has been approved.', THEME_TEXTDOMAIN);
}

?>
<article id="div-comment-<?php comment_ID(); ?>" class="uk-comment uk-visible-toggle" tabindex="-1">
    <header class="uk-comment-header uk-position-relative">
        <div class="uk-grid-medium uk-flex-middle" uk-grid>
            <div class="uk-width-auto">
				<?php
				if ( 0 !== $args['avatar_size'] ) {
					echo get_avatar($comment, $args['avatar_size']);
				}
				?>
            </div>
            <div class="uk-width-expand">
                <h4 class="uk-comment-title uk-margin-remove">
                    <a class="uk-link-reset" href="#">

						<?php
						$comment_author = get_comment_author_link($comment);

						if ( '0' === $comment->comment_approved && !$showPendingLinks ) {
							$comment_author = get_comment_author($comment);
						}
						echo $comment_author;
						?>
                    </a>
                </h4>
                <p class="uk-comment-meta uk-margin-remove-top">
					<?php comment_date(); ?>
                </p>
            </div>
        </div>
        <div class="uk-position-top-right uk-position-small uk-hidden-hover">
			<?php edit_comment_link(__('Edit'), '<span class="edit-link">', '</span> /'); ?>
			<?php
			comment_reply_link(array_merge($args, [
				'depth'     => $depth,
				'max_depth' => $args['max_depth'],
				'before'    => '',
				'after'     => '',
			]));
			?>
        </div>
    </header>


    <div class="uk-comment-body">
        <p>
			<?php
			comment_text($comment, array_merge($args, [
				'depth'     => $depth,
				'max_depth' => $args['max_depth'],
			]));
			?>
        </p>

		<?php if ( '0' === $comment->comment_approved ) : ?>
            <em class="comment-awaiting-moderation"><?php echo $moderationNote; ?></em>
		<?php endif; ?>
    </div>
</article>