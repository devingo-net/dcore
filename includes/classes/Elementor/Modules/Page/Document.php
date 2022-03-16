<?php

namespace DCore\Elementor\Modules\Page;

use ElementorPro\Modules\ThemeBuilder\Documents\Single_Page;

class Document extends Single_Page {
	protected static function get_editor_panel_categories () {
		$categories = [
			THEME_PREFIX . '_page' => [
				'title' => __('Page widgets', THEME_TEXTDOMAIN),
			],
		];

		return array_merge($categories, parent::get_editor_panel_categories());
	}
}
