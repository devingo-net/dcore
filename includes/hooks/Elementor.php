<?php

/**
 *  Elementor Hooks
 *
 * @category   Hooks
 * @version    1.0.0
 * @since      1.0.0
 */

use DCore\Elementor;
use DCore\Elementor\Modules\Header\Module as HeaderModule;
use DCore\Elementor\Modules\Menu\Module as MenuModule;
use DCore\Elementor\Modules\Page\Module as PageModule;
use DCore\Elementor\Modules\Product_Single\Module as ProductSingleModule;
use DCore\Theme;


function dCoreHeaderPrint () {
	Theme::getTemplatePart('header');
}

function dCoreFooterPrint () {
	Theme::getTemplatePart('footer');
}

function dCoreSinglePrint () {
	Theme::getTemplatePart('content-singular');
}

function dCoreArchivePrint () {
	Theme::getTemplatePart('content-archive');
}


add_action('dCoreHeader', 'dCoreHeaderPrint');
add_action('dCoreFooter', 'dCoreFooterPrint');
add_action('dCoreSingle', 'dCoreSinglePrint');
add_action('dCoreArchive', 'dCoreArchivePrint');

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( is_plugin_active( 'elementor/elementor.php' ) ) {
	add_action('elementor/theme/register_locations', [Elementor::class, 'registerLocations']);
	add_action('elementor/elements/categories_registered', [Elementor::class, 'registerCategories']);
}

if ( is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {

	if(class_exists('DCore\Elementor\Modules\Header\Module')) {
		add_action('elementor/init', [HeaderModule::class, 'init']);
		add_action('elementor/documents/register', [HeaderModule::class, 'registerDocuments'], 20);
	}

	if(class_exists('DCore\Elementor\Modules\Menu\Module')) {
		add_action('elementor/init', [MenuModule::class, 'init']);
		add_action('elementor/documents/register', [MenuModule::class, 'registerDocuments'], 20);
	}

	if(class_exists('DCore\Elementor\Modules\Page\Module')) {
		add_action('elementor/init', [PageModule::class, 'init']);
		add_action('elementor/documents/register', [PageModule::class, 'registerDocuments'], 20);
	}

	if(class_exists('DCore\Elementor\Modules\Product_Single\Module')) {
		add_action('elementor/init', [ProductSingleModule::class, 'init']);
		add_action('elementor/documents/register', [ProductSingleModule::class, 'registerDocuments'], 20);
	}

	add_action('elementor_pro/icons_manager_loaded', [Elementor::class, 'registerIcons']);
}
