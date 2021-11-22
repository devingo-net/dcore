<?php
/**
 * The template for displaying Author info
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

if ( (bool) get_the_author_meta('description') ) : ?>
    <div class="author-bio uk-card uk-card-default uk-padding-small uk-margin-medium-top uk-margin-medium-bottom
    uk-flex uk-flex-wrap
    uk-flex-wrap-around">
        <div class="author-title-wrapper">
            <div class="author-avatar vcard">
				<?= get_avatar(get_the_author_meta('ID'), 160) ?>
            </div>
        </div><!-- .author-name -->
        <div class="author-description uk-width-expand uk-margin-small-left">
            <h2 class="author-title heading-size-4">
		        <?php
		        printf(__('By %s', THEME_TEXTDOMAIN), esc_html(get_the_author()));
		        ?>
            </h2>
			<?= wp_kses_post(wpautop(get_the_author_meta('description'))) ?>
            <a class="author-link" href="<?= esc_url(get_author_posts_url(get_the_author_meta('ID'))) ?>"
               rel="author">
				<?php _e('View Archive <span aria-hidden="true">&rarr;</span>', THEME_TEXTDOMAIN); ?>
            </a>
        </div><!-- .author-description -->
    </div><!-- .author-bio -->
<?php endif; ?>
