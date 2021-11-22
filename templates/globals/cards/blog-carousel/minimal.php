<?php

/**
 * card template
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

/**
 * Template Name: Minimal design
 */

/**
 * @var \WP_Post $post
 */
?>

<div class="uk-card uk-card-default">
	<div class="uk-card-media-top">
		<?php the_post_thumbnail() ?>
	</div>
	<div class="uk-card-body">
		<h3 class="uk-card-title"><?php the_title() ?></h3>
		<p><?php the_category(',') ?></p>
	</div>
</div>