<?php

/**
 * custom carousel template
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

/**
 * @var string $carouselOptions
 * @var array $slideItems
 */
?>

<div class="slider-wrapper">
	<div class="swiper-container" data-theme-carousel='<?= esc_attr($carouselOptions) ?>'>
		<div class="swiper-wrapper">
			<?php foreach ( $slideItems as $slideItem ) { ?>
				<div class="swiper-slide"><?= $slideItem['content'] ?></div>
			<?php } ?>
		</div>
		<div class="slider-navs slider-button-prev uk-position-center-right"><i class="far fa-angle-right"></i>
		</div>
		<div class="slider-navs slider-button-next uk-position-center-left"><i class="far fa-angle-left"></i></div>

		<div class="slider-pagination swiper-pagination"></div>
		<div class="slider-scrollbar swiper-scrollbar"></div>
	</div>
</div>
