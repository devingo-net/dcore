<?php

namespace DCore\Elementor\Modules\Menu;

if (!class_exists('\ElementorPro\Modules\ThemeBuilder\Documents\Theme_Section_Document')) {
    class Document
    {

    }

    return;
}

use ElementorPro\Modules\ThemeBuilder\Documents\Theme_Section_Document;

/**
 * Class Menu Document
 * @package DCore\Elementor\Modules\Menu
 */
class Document extends Theme_Section_Document
{
    /**
     * @inheritDoc
     * @return array
     */
    public static function get_properties(): array
    {
        $properties = parent::get_properties();

        $properties['location'] = 'menu';

        return $properties;
    }

    protected static function get_editor_panel_categories(): array
    {
        $categories = [
            THEME_PREFIX . '_menu' => [
                'title' => __('Menu widgets', THEME_TEXTDOMAIN),
            ],
        ];

        return array_merge($categories, parent::get_editor_panel_categories());
    }


    protected static function get_site_editor_type(): string
    {
        return 'menu';
    }

    public static function get_title(): string
    {
        return __('Menu', THEME_TEXTDOMAIN);
    }

    public function get_name(): string
    {
        return 'menu';
    }

    protected static function get_site_editor_icon(): string
    {
        return 'eicon-apps';
    }

}
