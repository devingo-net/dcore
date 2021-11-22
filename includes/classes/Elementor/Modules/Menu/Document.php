<?php

namespace DCore\Elementor\Modules\Menu;

use ElementorPro\Modules\ThemeBuilder\Documents\Theme_Section_Document;

class Document extends Theme_Section_Document {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['location'] = 'menu';

		return $properties;
	}

	protected static function get_editor_panel_categories () {
		$categories = [
			THEME_PREFIX . '_menu' => [
				'title' => __('Menu widgets', THEME_TEXTDOMAIN),
			],
		];

		return array_merge($categories, parent::get_editor_panel_categories());
	}


	protected static function get_site_editor_type() : string {
		return 'menu';
	}

	public static function get_title() : string {
		return __( 'Menu', THEME_TEXTDOMAIN );
	}

	protected static function get_site_editor_icon() : string {
		return 'eicon-apps';
	}

}
