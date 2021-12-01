<?php

namespace DCore\Elementor\Modules\Header\Widgets;

use Elementor\Controls_Manager;
use DCore\Elementor\Widget;

class Search extends Widget {

	/**
	 * Search constructor.
	 *
	 * @param array $data
	 * @param null  $args
	 *
	 * @throws \Exception
	 */
	public function __construct ($data = [], $args = null) {
		$this->name        = 'Search_Widget';
		$this->title       = 'Search Widget';
		$this->icon        = 'fas fa-search';
		$this->categories  = ['prefix::header'];
		$this->jsTemplates = ['ajax-response.html'];

		parent::__construct($data, $args);
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls () : void {

		parent::register_controls();


		$this->start_controls_section('field_settings', [
			'label'    => __('Field', THEME_TEXTDOMAIN),
			'tab'      => Controls_Manager::TAB_CONTENT,
			'hasStyle' => true,
			'prefix'   => 'field',
			'selector' => '.search-form .search-field'
		]);

		$this->add_control('fieldPlaceholder', [
			'label'   => __('Placeholder', THEME_TEXTDOMAIN),
			'type'    => Controls_Manager::TEXT,
			'default' => __('Search in products...', THEME_TEXTDOMAIN),
		]);

		$this->end_controls_section();

		$this->start_controls_section('filter_settings', [
			'label'     => __('Filter', THEME_TEXTDOMAIN),
			'tab'       => Controls_Manager::TAB_CONTENT,
			'hasStyle'  => true,
			'prefix'    => 'filter',
			'selector'  => '.search-form .search-filter',
			'condition' => [
				'postType!' => 'all'
			]
		]);
		$this->add_control('filterActive', [
			'label'        => __('Category Filter', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('Show', THEME_TEXTDOMAIN),
			'label_off'    => __('Hide', THEME_TEXTDOMAIN),
			'return_value' => 'yes',
			'default'      => 'yes',
		]);
		$this->end_controls_section();

		$this->start_controls_section('button_settings', [
			'label'    => __('Button', THEME_TEXTDOMAIN),
			'tab'      => Controls_Manager::TAB_CONTENT,
			'hasStyle' => true,
			'prefix'   => 'button',
			'selector' => '.search-form .search-button'
		]);

		$this->add_control('buttonType', [
			'label'   => __('Button type', THEME_TEXTDOMAIN),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'icon' => [
					'title' => __('Icon', THEME_TEXTDOMAIN),
					'icon'  => 'fa fa-star',
				],
				'text' => [
					'title' => __('Text', THEME_TEXTDOMAIN),
					'icon'  => 'fa fa-edit',
				]
			],
			'default' => 'icon',
			'toggle'  => true,
		]);
		$this->add_control('buttonIcon', [
			'label'     => __('Icon', THEME_TEXTDOMAIN),
			'type'      => Controls_Manager::ICONS,
			'default'   => [
				'value'   => 'far fa-search',
				'library' => 'regular',
			],
			'condition' => [
				'buttonType' => 'icon'
			]
		]);
		$this->add_control('buttonText', [
			'label'     => __('Text', THEME_TEXTDOMAIN),
			'type'      => Controls_Manager::TEXT,
			'default'   => __('Search', THEME_TEXTDOMAIN),
			'condition' => [
				'buttonType' => 'text'
			]
		]);

		$this->end_controls_section();

		$this->start_controls_section('search_settings', [
			'label' => __('Search Settings', THEME_TEXTDOMAIN),
			'tab'   => Controls_Manager::TAB_CONTENT
		]);

		$this->add_control('postType', [
			'label'   => __('Post Type', THEME_TEXTDOMAIN),
			'type'    => Controls_Manager::SELECT,
			'default' => 'all',
			'options' => [
				'all'     => __('All', THEME_TEXTDOMAIN),
				'post'    => __('Post', THEME_TEXTDOMAIN),
				'product' => __('Product', THEME_TEXTDOMAIN),
			],
		]);

		$this->add_control('resultType', [
			'label'   => __('Results Type', THEME_TEXTDOMAIN),
			'type'    => Controls_Manager::SELECT,
			'default' => 'both',
			'options' => [
				'both' => __('Both', THEME_TEXTDOMAIN),
				'post' => __('Posts', THEME_TEXTDOMAIN),
				'tax'  => __('Taxonomy', THEME_TEXTDOMAIN),
			],
		]);
		$this->add_control('resultCount', [
			'label'   => __('Result Count', THEME_TEXTDOMAIN),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 1,
			'max'     => 20,
			'step'    => 1,
			'default' => 10,
		]);

		$this->add_control('ajaxActive', [
			'label'        => __('Ajax Search', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('Active', THEME_TEXTDOMAIN),
			'label_off'    => __('DeActive', THEME_TEXTDOMAIN),
			'return_value' => 'yes',
			'default'      => 'yes',
		]);

		$this->end_controls_section();
		$this->addStyleOptions(__('Parent', THEME_TEXTDOMAIN), '.search-form', 'form');
	}

	/**
	 * @inheritDoc
	 *
	 * @param array $settings
	 */
	protected function settingsParse (array &$settings) : void {
		$settings['categoriesList'] = [];
		$settings['widgetClasses']  = [];
		if ( $settings['ajaxActive'] === 'yes' ) {
			$settings['widgetClasses'][] = 'search-ajax-method';
		}
		if ( $settings['filterActive'] === 'yes' ) {
			$getProductCategories = get_terms([
				'taxonomy' => $settings['postType'] === 'post' ? 'category' : 'product_cat'
			]);
			if ( $settings['postType'] !== 'all' && !empty($getProductCategories) ) {
				$settings['categoriesList'] = $getProductCategories;
			}
		}
	}

}
