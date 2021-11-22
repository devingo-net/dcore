<?php
namespace DCore\Elementor\Modules\Assets\Icons;

use ElementorPro\Modules\AssetsManager\Classes\Assets_Base;
use Elementor\Settings;

class Font_Awesome_Pro extends  Assets_Base {
	public function get_name() {
		return __( 'Font Awesome Pro', THEME_TEXTDOMAIN );
	}

	public function get_type() {
		return prefixStr('font-awesome-pro');
	}

	public function replace_font_awesome_pro2( $settings ) {
		$json_url = THEME_ASSETS_SCRIPT_URI . 'lib/font-awesome-pro/%s.js';
		$icons['fa-regular'] = [
			'name' => 'fa-regular',
			'label' => __( 'Font Awesome - Regular Pro', THEME_TEXTDOMAIN ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'fa-',
			'displayPrefix' => 'far',
			'labelIcon' => 'fab fa-font-awesome-alt',
			'ver' => '5.12.0-pro',
			'fetchJson' => sprintf( $json_url, 'regular' ),
			'native' => true,
		];
		$icons['fa-solid'] = [
			'name' => 'fa-solid',
			'label' => __( 'Font Awesome - Solid Pro', THEME_TEXTDOMAIN ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'fa-',
			'displayPrefix' => 'fas',
			'labelIcon' => 'fab fa-font-awesome',
			'ver' => '5.12.0-pro',
			'fetchJson' => sprintf( $json_url, 'solid' ),
			'native' => true,
		];
		$icons['fa-brands'] = [
			'name' => 'fa-brands',
			'label' => __( 'Font Awesome - Brands Pro', THEME_TEXTDOMAIN ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'fa-',
			'displayPrefix' => 'fab',
			'labelIcon' => 'fab fa-font-awesome-flag',
			'ver' => '5.12.0-pro',
			'fetchJson' => sprintf( $json_url, 'brands' ),
			'native' => true,
		];
		$icons['fa-duotone'] = [
			'name' => 'fa-duotone',
			'label' => __( 'Font Awesome - Duotone Pro', THEME_TEXTDOMAIN ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'fa-',
			'displayPrefix' => 'fad',
			'labelIcon' => 'fad fa-flag',
			'ver' => '5.12.0-pro',
			'fetchJson' => sprintf( $json_url, 'duotone' ),
			'native' => true,
		];
		// remove Free
		unset(
			$settings['fa-solid'],
			$settings['fa-regular'],
			$settings['fa-light'],
			$settings['fa-duotone'],
			$settings['fa-thin'],
			$settings['fa-brands']
		);
		return array_merge( $icons, $settings );
	}

	public function enqueue_kit_js() {
		wp_enqueue_style( prefixStr('font-awesome-pro'), THEME_ASSETS_URI . 'font/fontawesome/css/all.css', [], defined('ELEMENTOR_PRO_VERSION') ? ELEMENTOR_PRO_VERSION : 1 );
	}


	protected function actions() {
		parent::actions();
		add_filter( 'elementor/icons_manager/native', [ $this, 'replace_font_awesome_pro2' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_kit_js' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_kit_js' ] );
	}
}
