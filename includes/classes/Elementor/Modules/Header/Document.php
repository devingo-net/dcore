<?php

namespace DCore\Elementor\Modules\Header;

if (!class_exists('\ElementorPro\Modules\ThemeBuilder\Documents\Header')) {
    class Document
    {

    }

    return;
}

use ElementorPro\Modules\ThemeBuilder\Documents\Header;

class Document extends Header
{
    protected static function get_editor_panel_categories()
    {
        $categories = [
            THEME_PREFIX . '_header' => [
                'title' => __('Header widgets', THEME_TEXTDOMAIN),
            ],
        ];

        return array_merge($categories, parent::get_editor_panel_categories());
    }
}
