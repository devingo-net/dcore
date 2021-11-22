<?php

/**
 *  Elementor Init
 *
 * @category   Elementor
 * @version    1.0.0
 * @since      1.0.0
 */

namespace DCore;

use DCore\Elementor\Modules\Assets\Icons\Font_Awesome_Pro;

class Elementor {

	/**
	 * register elementor categories
	 *
	 * @param \Elementor\Elements_Manager $elementorManager
	 */
	public static function registerCategories ($elementorManager) : void {
		$elementorManager->add_category(THEME_PREFIX . '_header', [
			'title' => __('Header widgets', THEME_TEXTDOMAIN)
		]);
		$elementorManager->add_category(THEME_PREFIX . '_menu', [
			'title' => __('Menu widgets', THEME_TEXTDOMAIN),
		]);
		$elementorManager->add_category(THEME_PREFIX . '_page', [
			'title' => __('Page widgets', THEME_TEXTDOMAIN),
		]);
		$elementorManager->add_category(THEME_PREFIX . '_product_single', [
			'title' => __('Product Single widgets', THEME_TEXTDOMAIN),
		]);
	}

	/**
	 * register elementor locations
	 *
	 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementorThemeManager
	 */
	public static function registerLocations ($elementorThemeManager) : void {

		$elementorThemeManager->register_location('menu');

		$elementorThemeManager->register_core_location('header', [
			'hook'         => 'dCoreHeader',
			'remove_hooks' => ['dCoreHeaderPrint'],
		]);
		$elementorThemeManager->register_core_location('footer', [
			'hook'         => 'dCoreFooter',
			'remove_hooks' => ['dCoreFooterPrint'],
		]);
		$elementorThemeManager->register_core_location('single', [
			'hook'         => 'dCoreSingle',
			'remove_hooks' => ['dCoreSinglePrint'],
		]);
		$elementorThemeManager->register_core_location('archive', [
			'hook'         => 'dCoreArchive',
			'remove_hooks' => ['dCoreArchivePrint'],
		]);
	}

	/**
	 * @param array|false $widgetConfigs
	 *
	 * @return array|false
	 */
	public static function getWidgetStyles ($widgetConfigs = []) {
		if ( empty($widgetConfigs) ) {
			$widgetConfigs = Configs::$shortcodes;
		}
		if($widgetConfigs === false){
			return [];
		}

		if ( !isset($widgetConfigs['templates']) || empty($widgetConfigs['templates']) ) {
			return false;
		}
		$stylesList = [];
		foreach ( $widgetConfigs['templates'] as $key => $template_item ) {
			$stylesList[] = [
				'name'            => $key,
				'title'           => $template_item['name'] ?? $key,
				'disabledOptions' => $styleData['disabledOptions'] ?? [],
				'template'        => $template_item['dir'],
				'colors'          => $template_item['manifest']['colors'] ?? [],
			];
		}

		return $stylesList;
	}

	/**
	 * @param \ElementorPro\Modules\AssetsManager\AssetTypes\Icons_Manager $icons_manager
	 */
	public static function registerIcons ($icons_manager) : void {
		update_option('elementor_font_awesome_pro_kit_id', '');
		$icons_manager->add_icon_type(prefixStr('font-awesome-pro'), new Font_Awesome_Pro());
	}

}
