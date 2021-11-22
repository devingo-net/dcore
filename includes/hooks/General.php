<?php

/**
 *  General Hooks
 *
 * @category    Hooks
 * @version     1.0.0
 * @since       1.0.0
 */

use DCore\Theme;

/**
 * After Setup Theme
 */

function dcAfterSetupTheme () {
	global $content_width;
	if ( !isset($content_width) ) {
		$content_width = 580;
	}

	add_theme_support('automatic-feed-links');
	add_theme_support('custom-background', [
		'default-color' => 'blue',
	]);
	add_theme_support('title-tag');
	add_theme_support('html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'script',
		'style',
	]);
	add_theme_support('align-wide');
	add_theme_support('responsive-embeds');
	add_theme_support('customize-selective-refresh-widgets');
	add_theme_support('post-thumbnails');
	add_theme_support('woocommerce');

	if ( getShopOptions('product-gallery-zoom', '1') === '1' ) {
		add_theme_support('wc-product-gallery-zoom');
	}

	load_theme_textdomain(THEME_TEXTDOMAIN, THEME_LANGS_DIR);

	register_nav_menus([
		'primary_menu' => __('Primary Menu', THEME_TEXTDOMAIN),
		'footer_menu'  => __('Footer Menu', THEME_TEXTDOMAIN),
	]);
}

function dcSidebarWidgetsInit () {

	register_sidebar([
		'name'          => __('Pages', THEME_TEXTDOMAIN),
		'id'            => 'page_sidebar',
		'before_widget' => '<section id="%s" class="sidebar-widget %s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	]);
	register_sidebar([
		'name'          => __('Blog', THEME_TEXTDOMAIN),
		'id'            => 'blog_sidebar',
		'before_widget' => '<section id="%s" class="sidebar-widget %s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	]);
	register_sidebar([
		'name'          => __('Shop Archive', THEME_TEXTDOMAIN),
		'id'            => 'shop_sidebar',
		'before_widget' => '<section id="%s" class="sidebar-widget %s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	]);

}

function dcBodyClasses ($classes) {
    if(!function_exists('WC')){return $classes;}

	if ( is_singular('product') && getShopOptions('product-gallery-zoom', '1') === '1' ) {
		$classes[] = 'product-gallery-zoom';
	}

	if ( getShopOptions('archive-ajax-filters', '0') === '1' && (is_tax(['product_cat','product_tag']) || is_shop())) {
		$classes[] = 'has-ajax-filter';
	}
	return $classes;
}

function themeOptionFontIcons ($icons) {

	$duoToneList          = Theme::getJsonFileArray(THEME_ASSETS_SCRIPT_URI . 'lib/font-awesome-pro/duotone.js');
	$duoToneList['icons'] = $duoToneList['icons'] ?? [];
	if ( !empty($duoToneList['icons']) ) {
		foreach ( $duoToneList['icons'] as $key => $val ) {
			$duoToneList['icons'][$key] = 'fad fa-' . $val;
		}

	}

	$brandsList          = Theme::getJsonFileArray(THEME_ASSETS_SCRIPT_URI . 'lib/font-awesome-pro/brands.js');
	$brandsList['icons'] = $brandsList['icons'] ?? [];
	if ( !empty($brandsList['icons']) ) {
		foreach ( $brandsList['icons'] as $key => $val ) {
			$brandsList['icons'][$key] = 'fab fa-' . $val;
		}

	}

	$lightList          = Theme::getJsonFileArray(THEME_ASSETS_SCRIPT_URI . 'lib/font-awesome-pro/light.js');
	$lightList['icons'] = $lightList['icons'] ?? [];
	if ( !empty($lightList['icons']) ) {
		foreach ( $lightList['icons'] as $key => $val ) {
			$lightList['icons'][$key] = 'fal fa-' . $val;
		}

	}

	$regularList          = Theme::getJsonFileArray(THEME_ASSETS_SCRIPT_URI . 'lib/font-awesome-pro/regular.js');
	$regularList['icons'] = $regularList['icons'] ?? [];
	if ( !empty($regularList['icons']) ) {
		foreach ( $regularList['icons'] as $key => $val ) {
			$regularList['icons'][$key] = 'far fa-' . $val;
		}

	}

	$solidList          = Theme::getJsonFileArray(THEME_ASSETS_SCRIPT_URI . 'lib/font-awesome-pro/regular.js');
	$solidList['icons'] = $solidList['icons'] ?? [];
	if ( !empty($solidList['icons']) ) {
		foreach ( $solidList['icons'] as $key => $val ) {
			$solidList['icons'][$key] = 'fas fa-' . $val;
		}

	}

	return [
		[
			'title' => __('DouTone icons', THEME_TEXTDOMAIN),
			'icons' => $duoToneList['icons']
		],
		[
			'title' => __('Brands icons', THEME_TEXTDOMAIN),
			'icons' => $brandsList['icons']
		],
		[
			'title' => __('Regular icons', THEME_TEXTDOMAIN),
			'icons' => $regularList['icons']
		],
		[
			'title' => __('Solid icons', THEME_TEXTDOMAIN),
			'icons' => $solidList['icons']
		]
	];

}

function dcEnableExtendedUpload ($mime_types = []) {
	$mime_types['obj'] = 'text/plain';
	$mime_types['mtl'] = 'text/plain';

	$mime_types['woff']  = 'font/woff|application/font-woff|application/x-font-woff|application/octet-stream';
	$mime_types['woff2'] = 'font/woff2|application/octet-stream|font/x-woff2';
	$mime_types['ttf']   = 'application/x-font-ttf|application/octet-stream|font/ttf';
	$mime_types['svg']   = 'image/svg+xml|application/octet-stream|image/x-svg+xml';
	$mime_types['eot']   = 'application/vnd.ms-fontobject|application/octet-stream|application/x-vnd.ms-fontobject';

	return $mime_types;

}

function dcFixCheckFiletypeAndExt ($data, $file, $filename, $mimes) {
	if ( !empty($data['ext']) && !empty($data['type']) ) {
		return $data;
	}

	$registered_file_types = dcEnableExtendedUpload();
	$filetype              = wp_check_filetype($filename, $mimes);

	if ( !isset($registered_file_types[$filetype['ext']]) ) {
		return $data;
	}

	return [
		'ext'             => $filetype['ext'],
		'type'            => explode('|', $filetype['type'])[0],
		'proper_filename' => $data['proper_filename'],
	];
}


add_action('after_setup_theme', 'dcAfterSetupTheme');
add_action('widgets_init', 'dcSidebarWidgetsInit');
add_filter('body_class', 'dcBodyClasses');
add_filter('csf_field_icon_add_icons', 'themeOptionFontIcons');
add_filter('upload_mimes', 'dcEnableExtendedUpload');
add_filter('wp_check_filetype_and_ext', 'dcFixCheckFiletypeAndExt', 10, 4);