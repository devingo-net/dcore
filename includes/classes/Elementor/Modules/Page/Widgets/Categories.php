<?php

namespace DCore\Elementor\Modules\Page\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use DCore\Elementor\Control_Types\Group_Control_Query;
use DCore\Woocommerce\Products_Renderer;
use DCore\Elementor\Widget;

/**
 * Class Categories
 *
 * @package DCore\Elementor\Modules\Page\Widgets
 */
class Categories extends Widget {

	/**
	 * Categories constructor.
	 *
	 * @param array $data
	 * @param null  $args
	 *
	 * @throws \Exception
	 */
	public function __construct ($data = [], $args = null) {
		$this->name       = 'Categories_Widget';
		$this->title      = 'Categories Widget';
		$this->icon       = 'fad fa-folders';
		$this->categories = ['prefix::page'];
		$this->cardStyle  = 'category';

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
		$this->addCardStyles();

		$this->add_control('termsToShow', [
			'label'   => __('Query', THEME_TEXTDOMAIN),
			'type'    => Controls_Manager::SELECT,
			'default' => 'latest',
			'options' => [
				'latest'  => __('Latest', THEME_TEXTDOMAIN),
				'parents' => __('Parents', THEME_TEXTDOMAIN),
				'top'     => __('Top Categories', THEME_TEXTDOMAIN),
				'custom'  => __('Custom', THEME_TEXTDOMAIN),
			],
		]);

		$this->add_control('termsSelect', [
			'type'         => 'query',
			'multiple'     => true,
			'autocomplete' => [
				'object'   => 'tax',
				'display'  => 'detailed',
				'by_field' => 'term_taxonomy_id',
				'query'    => [
					'taxonomy' => 'product_cat'
				],
			],
			'condition'    => [
				'termsToShow' => 'custom'
			]
		]);

		$this->add_control('termsCount', [
			'label'   => __('Count', THEME_TEXTDOMAIN),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'default' => 8
		]);

		$this->end_controls_section();

		$this->addWidgetStyles();


		$this->addSwiperCarouselOptions([
			'slidesPerView_desktop' => 6,
			'slidesPerView_tablet'  => 4,
			'slidesPerView_mobile'  => 2
		]);

	}


	public function settingsParse (array &$settings) : void {
		$settings['carouselOptions'] = $this->getSwiperCarouselOptions($settings);
		$termArgs                    = [
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'number'     => $settings['termsCount']
		];

		if ( $settings['termsToShow'] === 'latest' ) {
			$termArgs['orderby'] = 'id';
			$termArgs['order']   = 'DESC';
		}

		if ( $settings['termsToShow'] === 'top' ) {
			$termArgs['orderby'] = 'count';
			$termArgs['order']   = 'DESC';
		}

		if ( $settings['termsToShow'] === 'parents' ) {
			$termArgs['parent']  = 0;
			$termArgs['orderby'] = 'id';
			$termArgs['order']   = 'DESC';
		}

		if ( $settings['termsToShow'] === 'custom' && !empty($settings['termsSelect'])) {
			$termArgs['include']  = $settings['termsSelect'];
		}

		$settings['categories'] = get_terms($termArgs);
	}

}
