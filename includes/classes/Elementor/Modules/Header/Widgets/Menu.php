<?php

namespace DCore\Elementor\Modules\Header\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use DCore\Elementor\Widget;

class Menu extends Widget {

	/**
	 * Menu constructor.
	 *
	 * @param array $data
	 * @param null  $args
	 *
	 * @throws \Exception
	 */
	public function __construct ($data = [], $args = null) {
		$this->name       = 'Menu_Widget';
		$this->title      = 'Menu Widget';
		$this->icon       = 'fad fa-list-ul';
		$this->categories = ['prefix::header'];

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


		$this->start_controls_section('Menu_settings', [
			'label'    => __('Menu', THEME_TEXTDOMAIN),
			'tab'      => Controls_Manager::TAB_CONTENT,
			'hasStyle' => true,
			'prefix'   => 'menu',
			'selector' => '.main-nav-menu .menu-inner',
			'excludes' => ['font']
		]);


		$menus = $this->getAvailableMenus();

		if ( empty($menus) ) {
			$this->add_control('menu', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<strong>' . __('There are no menus in your site.', THEME_TEXTDOMAIN) . '</strong><br>' . sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', THEME_TEXTDOMAIN), admin_url('nav-menus.php?action=edit&menu=0')),
				'separator'       => 'after',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]);
		} else {
			$this->add_control('menu', [
				'label'        => __('Menu', THEME_TEXTDOMAIN),
				'type'         => Controls_Manager::SELECT,
				'options'      => $menus,
				'default'      => array_keys($menus)[0],
				'save_default' => true,
				'separator'    => 'after',
				'description'  => sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', THEME_TEXTDOMAIN), admin_url('nav-menus.php')),
			]);
		}

		$this->end_controls_section();

		$this->addStyleOptions(__('Item', THEME_TEXTDOMAIN), '.main-nav-menu .menu-inner>li{{mode}}>a', 'menuItem');

		$this->addStyleOptions(__('Dropdown', THEME_TEXTDOMAIN), '.main-nav-menu .menu-inner>li.menu-design-type-normal li{{mode}}>a', 'menuDropDown');

		$this->addStyleOptions(__('Icon', THEME_TEXTDOMAIN), '.main-nav-menu .menu-inner>li{{mode}}>a>.menu-icon', 'menuIcon',[],['flex','padding','font']);
	}

	/**
	 * Parse Setting
	 *
	 * @param array $settings
	 *
	 * @return void
	 */
	public function settingsParse (array &$settings) : void {
		$settings['menu'] = $settings['menu'] ?? false;
	}

}
