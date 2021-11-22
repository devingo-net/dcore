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
 * @var string $title
 * @var array $image
 * @var string $description
 * @var string $linkAttr
 */
?>
<div class="uk-position-cover" uk-slideshow-parallax="scale: 1.2,1.2,1">
    <img src="<?= $image['url'] ?? '' ?>" alt="<?= $title ?>"
         uk-cover>
</div>
<div class="uk-position-cover" style="background: rgba(0,0,0,.5)"></div>
<div class="uk-position-center uk-position-medium uk-text-center">
    <div uk-slideshow-parallax="scale: 1,1,0.8">
        <h2 uk-slideshow-parallax="x: 200,0,0"><a class="primary_color-normal" <?= $linkAttr ?>><?= $title ?></a></h2>
		<?php if ( !empty($description) ) { ?>
            <p uk-slideshow-parallax="x: 400,0,0;"><?= $description ?></p>
		<?php } ?>
    </div>
</div>