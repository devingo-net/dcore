<?php

/**
 *  Elementor Hooks
 *
 * @category   Hooks
 * @version    1.0.0
 * @since      1.0.0
 */

use DCore\Elementor;
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

function dcWoocommerceGetCatalogOrderingArgs($args, $orderby, $order){
    if ($orderby === 'view'){
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = \DCore\Features\Statistics::countOptionName;
    }
    if ($orderby === 'sale'){
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = 'total_sales';
    }
    return $args;
}

add_action('dCoreHeader', 'dCoreHeaderPrint');
add_action('dCoreFooter', 'dCoreFooterPrint');
add_action('dCoreSingle', 'dCoreSinglePrint');
add_action('dCoreArchive', 'dCoreArchivePrint');
add_filter('woocommerce_get_catalog_ordering_args', 'dcWoocommerceGetCatalogOrderingArgs',10,3);

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'elementor/elementor.php' ) ) {
	add_action('elementor/theme/register_locations', [Elementor::class, 'registerLocations']);
	add_action('elementor/elements/categories_registered', [Elementor::class, 'registerCategories']);
}

if ( is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {
	if(class_exists('DCore\Elementor\Modules\Header\Module')) {
		add_action('elementor/init', ['DCore\Elementor\Modules\Header\Module', 'init']);
		add_action('elementor/documents/register', ['DCore\Elementor\Modules\Header\Module', 'registerDocuments'], 20);
	}

	if(class_exists('DCore\Elementor\Modules\Menu\Module')) {
		add_action('elementor/init', ['DCore\Elementor\Modules\Menu\Module', 'init']);
		add_action('elementor/documents/register', ['DCore\Elementor\Modules\Menu\Module', 'registerDocuments'], 20);
	}

	if(class_exists('DCore\Elementor\Modules\Page\Module')) {
		add_action('elementor/init', ['DCore\Elementor\Modules\Page\Module', 'init']);
		add_action('elementor/documents/register', ['DCore\Elementor\Modules\Page\Module', 'registerDocuments'], 20);
	}

	if(class_exists('DCore\Elementor\Modules\Product_Single\Module')) {
		add_action('elementor/init', ['DCore\Elementor\Modules\Product_Single\Module', 'init']);
		add_action('elementor/documents/register', ['DCore\Elementor\Modules\Product_Single\Module', 'registerDocuments'], 20);
	}
}
