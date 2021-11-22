<?php

/**
 * category card template
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
 * @var \WP_Term $category
 */

$thumbnailID = get_term_meta( $category->term_id, 'thumbnail_id', true );
?>
<a href="<?= get_term_link($category); ?>">
    <div class="uk-card uk-card-default">
        <div class="uk-card-badge uk-label"><?= $category->count ?></div>
        <div class="uk-card-media-top">
			<?php
			if (!empty($thumbnailID)){
				echo wp_get_attachment_image($thumbnailID);
			}
			?>
        </div>
        <div class="uk-card-body">
            <h3 class="uk-card-title"><?= $category->name ?></h3>
            <p><?= $category->description ?></p>
        </div>
    </div>
</a>