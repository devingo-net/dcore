<?php

namespace DCore\Elementor\Modules\Page;

use ElementorPro\Modules\ThemeBuilder\Documents\Single_Base;

class Document extends Single_Base {

	public static function get_properties () : array {
		$properties = parent::get_properties();

		$properties['location'] = 'page';

		return $properties;
	}

	protected static function get_editor_panel_categories () {
		$categories = [
			THEME_PREFIX . '_page' => [
				'title' => __('Page widgets', THEME_TEXTDOMAIN),
			],
		];

		return array_merge($categories, parent::get_editor_panel_categories());
	}


	protected static function get_site_editor_type () : string {
		return 'page';
	}

	public static function get_title () : string {
		return __('Page', THEME_TEXTDOMAIN);
	}

	protected static function get_site_editor_icon () : string {
		return 'eicon-page';
	}

}
