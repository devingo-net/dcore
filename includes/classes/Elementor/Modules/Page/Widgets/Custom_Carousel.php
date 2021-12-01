<?php

namespace DCore\Elementor\Modules\Page\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use DCore\Elementor\Widget;

/**
 * Class Custom_Carousel
 *
 * @package DCore\Elementor\Modules\Page\Widgets
 */
class Custom_Carousel extends Widget {

	/**
	 * Custom Carousel constructor.
	 *
	 * @param array $data
	 * @param null  $args
	 *
	 * @throws \Exception
	 */
	public function __construct ($data = [], $args = null) {
		$this->name       = 'Custom_Carousel_Widget';
		$this->title      = 'Custom Carousel Widget';
		$this->icon       = 'fas fa-code';
		$this->categories = ['prefix::page'];

		parent::__construct($data, $args);
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls () : void {
		parent::register_controls();
		$this->start_controls_section('Content_settings', [
			'label' => __('Contents', THEME_TEXTDOMAIN),
			'tab'   => Controls_Manager::TAB_CONTENT,
		]);


		$slideItemsRepeater = new Repeater();
		$slideItemsRepeater->add_control('title', [
			'label'       => __('Title', THEME_TEXTDOMAIN),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		]);
		$slideItemsRepeater->add_control('content', [
			'label'       => __('Content', THEME_TEXTDOMAIN),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => '',
			'placeholder' => __('Type your content here', THEME_TEXTDOMAIN),
		]);


		$this->add_control('slideItems', [
			'label'       => __('Slide Items', THEME_TEXTDOMAIN),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $slideItemsRepeater->get_controls(),
			'title_field' => '{{{ title }}}',
		]);



		$this->end_controls_section();
		$this->addWidgetStyles();

		$this->addSwiperCarouselOptions();
	}


	public function settingsParse (array &$settings) : void {
		$settings['carouselOptions'] = $this->getSwiperCarouselOptions($settings);
	}

}
