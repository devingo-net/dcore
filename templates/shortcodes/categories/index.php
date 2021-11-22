<?php

/**
 *  Categories widget shortcode
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

use DCore\Theme;

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

/**
 * @var string $carouselOptions
 * @var string $cardStyle
 * @var array  $categories
 */
?>
<div class="slider-wrapper">
    <div class="swiper-container" data-theme-carousel='<?= esc_attr($carouselOptions) ?>'>
        <div class="swiper-wrapper">
			<?php foreach ( $categories as $category ) { ?>
                <div class="swiper-slide"><?php Theme::getTemplatePart($cardStyle, [
						'category' => $category
					], true); ?></div>
			<?php } ?>
        </div>
        <div class="slider-navs slider-button-prev uk-position-center-right"><i class="far fa-angle-right"></i></div>
        <div class="slider-navs slider-button-next uk-position-center-left"><i class="far fa-angle-left"></i></div>

        <div class="slider-pagination swiper-pagination"></div>
        <div class="slider-scrollbar swiper-scrollbar"></div>
    </div>
</div>