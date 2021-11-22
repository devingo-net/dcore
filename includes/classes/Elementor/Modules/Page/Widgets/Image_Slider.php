<?php

namespace DCore\Elementor\Modules\Page\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use DCore\Elementor\Widget;

class Image_Slider extends Widget {

	/**
	 * Image Slider constructor.
	 *
	 * @param array $data
	 * @param null  $args
	 *
	 * @throws \Exception
	 */
	public function __construct ($data = [], $args = null) {
		$this->name       = 'Image_Slider_Widget';
		$this->title      = 'Image Slider Widget';
		$this->icon       = 'fad fa-images';
		$this->categories = ['prefix::page'];
		$this->cardStyle = 'image';

		parent::__construct($data, $args);
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls () : void {

		parent::register_controls();

		$this->addSwiperCarouselOptions([
			'slidesPerView_desktop' =>  1,
			'slidesPerView_tablet' =>  1,
			'slidesPerView_mobile' =>  1,
		]);


		$this->start_controls_section('Items_settings', [
			'label' => __('Items', THEME_TEXTDOMAIN),
			'tab'   => Controls_Manager::TAB_CONTENT,
		]);

		$this->add_responsive_control(
			'sliderHeight',
			[
				'type' => Controls_Manager::SLIDER,
				'label'      => __('Height', THEME_TEXTDOMAIN),
				'size_units' => ['px', 'vh'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'vh'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 630,
				],
				'selectors'  => [
					'{{WRAPPER}} .swiper-wrapper .swiper-slide' => 'min-height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .custom-slide' => 'min-height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->addCardStyles();

		$slideItemsRepeater = new Repeater();
		$slideItemsRepeater->add_control('image', [
			'label' => __('Image', THEME_TEXTDOMAIN),
			'type'  => Controls_Manager::MEDIA
		]);
		$slideItemsRepeater->add_control('title', [
			'label'       => __('Title', THEME_TEXTDOMAIN),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		]);
		$slideItemsRepeater->add_control('link', [
			'label'       => __('Link', THEME_TEXTDOMAIN),
			'type'        => Controls_Manager::URL,
			'placeholder' => __('https://your-link.com', THEME_TEXTDOMAIN),
			'dynamic'     => [
				'active' => true,
			],
			'default'     => [
				'url' => '#',
			],
		]);
		$slideItemsRepeater->add_control('description', [
			'label'       => __('Description', THEME_TEXTDOMAIN),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => '',
			'placeholder' => __('Type your description here', THEME_TEXTDOMAIN),
		]);


		$this->add_control('slideItems', [
			'label'       => __('Slide Items', THEME_TEXTDOMAIN),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $slideItemsRepeater->get_controls(),
			'title_field' => '{{{ title }}}',
		]);

		$this->end_controls_section();
		$this->addWidgetStyles();

	}

	public function settingsParse (array &$settings) : void {
		$settings['widgetClasses'] = [];

		if ( isset($settings['cardStyle']) && !file_exists($settings['cardStyle']) ) {
			$settings['cardStyle'] = false;
		}

		$settings['carouselOptions']    =   $this->getSwiperCarouselOptions($settings);

		if ( !empty($settings['slideItems']) ) {
			$loopIndex = 0;
			foreach ( $settings['slideItems'] as &$slideItem ) {
				$slideItem['linkAttr'] = 'href=""';
				if ( !empty($slideItem['link']['url']) ) {
					$this->add_link_attributes('slide-item-link-' . $loopIndex, $slideItem['link']);

					$slideItem['linkAttr'] = $this->get_render_attribute_string('slide-item-link-' . $loopIndex);
					$loopIndex++;
				}
			}
			unset($slideItem);
		}
	}

}
