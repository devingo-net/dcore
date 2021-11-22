<?php
/**
 * Displays the featured image
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

if ( has_post_thumbnail() && !post_password_required() ) {
	?>

    <figure class="featured-media">
        <div class="featured-media-inner section-inner">
			<?php
			the_post_thumbnail();

			$caption = get_the_post_thumbnail_caption();

			if ( $caption ) {
				?>

                <figcaption class="wp-caption-text"><?= wp_kses_post($caption) ?></figcaption>

				<?php
			}
			?>
        </div>
    </figure>
	<?php
}
