<?php

/**
 *  Image Slider widget shortcode
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

use DCore\Theme;

/**
 * @var string $carouselOptions
 * @var string $cardStyle
 * @var array  $slideItems
 * @var array  $sliderHeight
 */
?>
<div class="slider-wrapper swiper-container" data-theme-carousel='<?= esc_attr($carouselOptions) ?>'>
    <div class="swiper-wrapper">
        <?php if ( !empty($slideItems) ) { ?>
            <?php foreach ( $slideItems as $slideItem ) {
                ?>
                <div class="swiper-slide">
                    <?php Theme::getTemplatePart($cardStyle, $slideItem, true); ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="slider-navs slider-button-prev uk-position-center-right"><i class="far fa-angle-right"></i></div>
    <div class="slider-navs slider-button-next uk-position-center-left"><i class="far fa-angle-left"></i></div>

    <div class="slider-pagination swiper-pagination"></div>
    <div class="slider-scrollbar swiper-scrollbar"></div>
</div>