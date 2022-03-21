<?php

/**
 *  blog options
 *
 * @category   settings
 * @version    1.0.0
 * @since      1.0.0
 */

use DCore\Theme;

global $themeOptionsName;

CSF::createOptions($themeOptionsName, [
	'menu_title'      => __('Theme Settings', THEME_TEXTDOMAIN),
	'framework_title' => __('Theme Settings', THEME_TEXTDOMAIN),
	'menu_slug'       => THEME_PREFIX . '-theme-settings',
	'theme'           => 'light',
	'footer_credit'   => THEME_NAME
]);

CSF::createSection($themeOptionsName, [
	'id'    => 'blog_options',
	'title' => __('Blog', THEME_TEXTDOMAIN),
	'icon'  => 'dashicons-before dashicons-admin-page',
]);


$blogCards = Theme::getCardsList('blog-post');
CSF::createSection($themeOptionsName, [
	'parent' => 'blog_options',
	'title'  => __('Archive', THEME_TEXTDOMAIN),
	'fields' => [
		[
			'id'      => 'blog-card-style',
			'type'    => 'select',
			'title'   => __('Card Style', THEME_TEXTDOMAIN),
			'default' => array_key_first($blogCards),
			'options' => $blogCards,
		],
		[
			'id'      => 'blog-archive-columns',
			'type'    => 'image_select',
			'title'   => __('Columns', THEME_TEXTDOMAIN),
			'options' => [
				'right-sidebar' => THEME_ASSETS_URI . 'image/columns/right-sidebar.png',
				'full-width'    => THEME_ASSETS_URI . 'image/columns/full-width.png',
				'left-sidebar'  => THEME_ASSETS_URI . 'image/columns/left-sidebar.png'
			],
			'default' => 'right-sidebar'
		]
	]
]);

CSF::createSection($themeOptionsName, [
	'parent' => 'blog_options',
	'title'  => __('Single', THEME_TEXTDOMAIN),
	'fields' => [
		[
			'id'      => 'blog-single-columns',
			'type'    => 'image_select',
			'title'   => __('Columns', THEME_TEXTDOMAIN),
			'options' => [
				'right-sidebar' => THEME_ASSETS_URI . 'image/columns/right-sidebar.png',
				'full-width'    => THEME_ASSETS_URI . 'image/columns/full-width.png',
				'left-sidebar'  => THEME_ASSETS_URI . 'image/columns/left-sidebar.png'
			],
			'default' => 'right-sidebar'
		]
	]
]);

CSF::createSection($themeOptionsName, [
	'id'    => 'style_options',
	'title' => __('Styles', THEME_TEXTDOMAIN),
	'icon'  => 'dashicons-before dashicons-admin-page',
]);


$configColors = Theme::getConfig('colors', [
	'primary'   => [
		'normal' => '#1e87f0',
		'dark'   => '#106bc6'
	],
	'secondary' => [
		'normal' => '#1e87f0',
		'dark'   => '#106bc6'
	],
]);

CSF::createSection($themeOptionsName, [
	'parent' => 'style_options',
	'title'  => __('Sizes', THEME_TEXTDOMAIN),
	'fields' => [
		[
			'id'          => 'container-size',
			'type'        => 'number',
			'title'       => __('Container Size', THEME_TEXTDOMAIN),
			'unit'        => 'px',
			'output'      => '.uk-container',
			'output_mode' => 'max-width',
			'default'     => 1200,
		]
	]
]);

CSF::createSection($themeOptionsName, [
	'parent' => 'style_options',
	'title'  => __('Colors', THEME_TEXTDOMAIN),
	'fields' => [
		[
			'id'      => 'primary-color',
			'type'    => 'color',
			'title'   => __('Primary color', THEME_TEXTDOMAIN),
			'default' => $configColors['primary']['normal'] ?? ''
		],
		[
			'id'      => 'primary-color-dark',
			'type'    => 'color',
			'title'   => __('Darken primary color', THEME_TEXTDOMAIN),
			'default' => $configColors['primary']['dark'] ?? ''
		],
		[
			'id'      => 'secondary-color',
			'type'    => 'color',
			'title'   => __('Secondary color', THEME_TEXTDOMAIN),
			'default' => $configColors['secondary']['normal'] ?? ''
		],
		[
			'id'      => 'secondary-color-dark',
			'type'    => 'color',
			'title'   => __('Darken secondary color', THEME_TEXTDOMAIN),
			'default' => $configColors['secondary']['dark'] ?? ''
		],
	]
]);
$fontsList = Theme::getFontsList();
CSF::createSection($themeOptionsName, [
	'parent' => 'style_options',
	'title'  => __('Typography', THEME_TEXTDOMAIN),
	'fields' => [
		[
			'id'      => 'site-font',
			'type'    => 'select',
			'title'   => __('Font family', THEME_TEXTDOMAIN),
			'options' => $fontsList + [
					'upload' => __('Upload custom font', THEME_TEXTDOMAIN)
				],
			'default' => array_key_first($fontsList)
		],
		[
			'id'         => 'custom-font-ttf',
			'type'       => 'upload',
			'title'      => __('Font ttf file', THEME_TEXTDOMAIN),
			'dependency' => ['site-font', '==', 'upload'],
		],
		[
			'id'         => 'custom-font-woff',
			'type'       => 'upload',
			'title'      => __('Font woff file', THEME_TEXTDOMAIN),
			'dependency' => ['site-font', '==', 'upload'],
		],
		[
			'id'         => 'custom-font-woff2',
			'type'       => 'upload',
			'title'      => __('Font woff2 file', THEME_TEXTDOMAIN),
			'dependency' => ['site-font', '==', 'upload'],
		],
		[
			'id'         => 'custom-font-svg',
			'type'       => 'upload',
			'title'      => __('Font svg file', THEME_TEXTDOMAIN),
			'dependency' => ['site-font', '==', 'upload'],
		],
		[
			'id'         => 'custom-font-eot',
			'type'       => 'upload',
			'title'      => __('Font eot file', THEME_TEXTDOMAIN),
			'dependency' => ['site-font', '==', 'upload'],
		],
	]
]);