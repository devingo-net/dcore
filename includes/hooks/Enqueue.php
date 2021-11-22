<?php

use DCore\Theme;

/**
 *  enqueue styles and scripts
 *
 * @category    Hooks
 * @version     1.0.0
 * @since       1.0.0
 */

/**
 * Enqueue Frontend scripts
 */
function dcEnqueueScripts () {
	if ( function_exists('is_checkout') && is_checkout() ) {
		wp_enqueue_script(THEME_PREFIX . '-theme-admin-global', THEME_ASSETS_SCRIPT_URI . 'leaflet.js');
	}
	wp_enqueue_script(THEME_PREFIX . '-theme-script', THEME_DIST_URI . '/script.js', ['wp-util']);
	wp_localize_script(THEME_PREFIX . '-theme-script', 'themeScriptParams', [
		'pageID'    => get_the_ID(),
		'ajaxNonce' => wp_create_nonce('ajax-nonce'),
		'ajaxURL'   => admin_url('admin-ajax.php'),
		'ajaxError' => __('An error occurred while processing!', THEME_TEXTDOMAIN),
	]);

	if ( is_singular() && comments_open() && get_option('thread_comments') ) {
		wp_enqueue_script('comment-reply');
	}
}

/**
 * Enqueue Frontend styles
 */
function dcEnqueueStyles () {
	if ( function_exists('is_checkout') && is_checkout() ) {
		wp_enqueue_style(THEME_PREFIX . '-theme-admin-global', THEME_ASSETS_STYLE_URI . 'leaflet.css');
	}

	wp_enqueue_style(THEME_PREFIX . '-theme-style', THEME_DIST_URI . '/style.css');
	wp_add_inline_style(THEME_PREFIX . '-theme-style', Theme::dynamicStyles());
}

/**
 * Enqueue Admin styles
 */
function dcAdminEnqueueStyles () {
	wp_enqueue_style(THEME_PREFIX . '-theme-admin-metabox', THEME_ASSETS_STYLE_URI . 'admin/metabox.css');
	wp_enqueue_style(THEME_PREFIX . '-theme-admin-style', THEME_ASSETS_STYLE_URI . 'admin/global.css');
	wp_enqueue_style(THEME_PREFIX . '-theme-admin-fontawesome', THEME_ASSETS_URI . 'font/fontawesome/css/all.css');
}

/**
 * Enqueue Admin scripts
 */
function dcAdminEnqueueScripts () {
	wp_enqueue_script(THEME_PREFIX . '-theme-admin-metabox', THEME_ASSETS_SCRIPT_URI . 'admin/metabox.js');
	wp_enqueue_script(THEME_PREFIX . '-theme-admin-global', THEME_ASSETS_SCRIPT_URI . 'admin/global.js');
	wp_localize_script(THEME_PREFIX . '-theme-admin-global', 'themeScriptParams', [
		'ajaxURL'      => admin_url('admin-ajax.php'),
		'confirmAlert' => __('Are you sure you want to perform this operation?', THEME_TEXTDOMAIN)
	]);
}


add_action('wp_enqueue_scripts', 'dcEnqueueScripts');
add_action('wp_enqueue_scripts', 'dcEnqueueStyles');
add_action('admin_enqueue_scripts', 'dcAdminEnqueueStyles');
add_action('admin_enqueue_scripts', 'dcAdminEnqueueScripts');
add_filter('woocommerce_enqueue_styles', '__return_empty_array');
add_filter('elementor/frontend/print_google_fonts', '__return_false');