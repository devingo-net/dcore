<?php

/**
 *
 *
 * @category   settings
 * @version    1.0.0
 * @since      1.0.0
 */

$pageMeta = 'page_meta';

CSF::createMetabox($pageMeta, [
	'title'          => __('Page Settings', THEME_TEXTDOMAIN),
	'post_type'      => 'page',
	'data_type'      => 'unserialize',
	'context'        => 'side',
	'page_templates' => ['templates/page-full-width.php', 'default']
]);

CSF::createSection($pageMeta, [
	'title'  => __('Sections', THEME_TEXTDOMAIN),
	'fields' => [
		[
			'id'      => 'page-title',
			'type'    => 'switcher',
			'title'   => __('Show page title', THEME_TEXTDOMAIN),
			'default' => "1"
		]
	]
]);
