<?php

/**
 *  initialize theme options
 *
 * @category   initialize
 * @version    1.0.0
 * @since      1.0.0
 */

require_once __DIR__ . '/framework/classes/setup.class.php';
if ( !class_exists('CSF') ) {
	return;
}
global $themeOptionsName;
$themeOptionsName = THEME_PREFIX . '-theme-options';

require_once __DIR__ . '/sections/theme.php';
require_once __DIR__ . '/sections/single/page.php';