<?php

namespace DCore\Elementor\Modules\Menu\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use DCore\Elementor\Widget;

class Tabbed_Menu extends Widget {

	/**
	 * Search constructor.
	 *
	 * @param array $data
	 * @param null  $args
	 *
	 * @throws \Exception
	 */
	public function __construct ($data = [], $args = null) {
		$this->name       = 'Tabbed_Menu_Widget';
		$this->title      = 'Tabbed Menu Widget';
		$this->icon       = 'fad fa-user';
		$this->categories = ['prefix::menu'];

		parent::__construct($data, $args);
	}


	/**
	 * get list of available menus
	 *
	 * @return array
	 */
	private function getAvailableMenus () {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[$menu->slug] = $menu->name;
		}

		return $options;
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls () : void {

		parent::register_controls();


		$this->start_controls_section('Tab_settings', [
			'label' => __('Tab', THEME_TEXTDOMAIN),
			'tab'   => Controls_Manager::TAB_CONTENT,
		]);
		$this->add_control('eventType', [
			'label'   => __('Event Type', THEME_TEXTDOMAIN),
			'type'    => Controls_Manager::SELECT,
			'default' => 'click',
			'options' => [
				'click' => __('Click', THEME_TEXTDOMAIN),
				'hover' => __('Hover', THEME_TEXTDOMAIN),
			],
		]);
		$this->end_controls_section();

		$this->start_controls_section('TabItems_settings', [
			'label'    => __('Tab Items', THEME_TEXTDOMAIN),
			'tab'      => Controls_Manager::TAB_CONTENT,
			'hasStyle' => true,
			'prefix'   => 'tabItems',
			'selector' => '.tabbed-menu .menu-tabs li{{mode}} a',
			'modes'    => [
				':normal'    => __('Normal', THEME_TEXTDOMAIN),
				'.uk-active' => __('Active', THEME_TEXTDOMAIN)
			],
		]);
		$tabItemsRepeater = new Repeater();
		$tabItemsRepeater->add_control('icon', [
			'label'   => __('Icon', THEME_TEXTDOMAIN),
			'type'    => Controls_Manager::ICONS,
			'default' => [
				'value'   => 'far fa-bars',
				'library' => 'regular',
			]
		]);
		$tabItemsRepeater->add_control('title', [
			'label'       => __('Title', THEME_TEXTDOMAIN),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		]);
		$tabItemsRepeater->add_control('link', [
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
		$menus = $this->getAvailableMenus();
		if ( empty($menus) ) {
			$tabItemsRepeater->add_control('menu', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<strong>' . __('There are no menus in your site.',
						THEME_TEXTDOMAIN) . '</strong><br>' . sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to create one.',
						THEME_TEXTDOMAIN), admin_url('nav-menus.php?action=edit&menu=0')),
				'separator'       => 'after',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]);
		} else {
			$tabItemsRepeater->add_control('menu', [
				'label'        => __('Menu', THEME_TEXTDOMAIN),
				'type'         => Controls_Manager::SELECT,
				'options'      => $menus,
				'default'      => array_keys($menus)[0],
				'save_default' => true,
				'separator'    => 'after',
				'description'  => sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.',
					THEME_TEXTDOMAIN), admin_url('nav-menus.php')),
			]);
		}
		$this->add_control('items', [
			'label'       => __('Tab Items', THEME_TEXTDOMAIN),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $tabItemsRepeater->get_controls(),
			'title_field' => '{{{ title }}}',
		]);
		$this->end_controls_section();

		$this->addStyleOptions(__('Tab Items Icon', THEME_TEXTDOMAIN), '.menu-tabs li{{mode}} a i', 'tabIcon', [], [], [
			':normal'    => __('Normal', THEME_TEXTDOMAIN),
			'.uk-active' => __('Active', THEME_TEXTDOMAIN)
		]);
	}

	public function settingsParse (array &$settings) : void {
		$settings['widgetClasses'] = [
			'tab-event-' . $settings['eventType']
		];


		if ( !empty($settings['items']) ) {
			$loopIndex = 0;
			foreach ( $settings['items'] as &$item ) {
				if ( $loopIndex === 0 ) {
					$item['first'] = true;
				}
				$item['menu'] = $item['menu'] ?? false;
				if ( !empty($item['link']['url']) ) {
					$this->add_link_attributes('tabs-link-' . $loopIndex, $item['link']);

					$item['linkAttr'] = $this->get_render_attribute_string('tabs-link-' . $loopIndex);
					$loopIndex++;
				}
			}
			unset($item);
		}

	}

}
