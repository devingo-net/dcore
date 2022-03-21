<?php

use DCore\Theme;

define('THEME_TEXTDOMAIN', Theme::getConfig('textdomain', 'dcore'));
define('THEME_NAME', Theme::getConfig('name', 'شاپ دیزاین'));
define('THEME_PREFIX', Theme::getConfig('prefix', 'dc'));

if(is_user_logged_in()){
	define('DC_CUI', get_current_user_id());
}

define('DSP', DIRECTORY_SEPARATOR);

define('THEME_DIR', get_template_directory());
define('THEME_INCLUDES_DIR', THEME_DIR . DSP . 'includes');
define('THEME_LANGS_DIR', THEME_INCLUDES_DIR . DSP . 'languages');
define('THEME_DIST_DIR', THEME_DIR . DSP . 'dist');
define('THEME_TEMPLATES_DIR', THEME_DIR . DSP . 'templates');
define('THEME_TEMPLATES_GLOBALS_DIR', THEME_TEMPLATES_DIR . DSP . 'globals');
define('THEME_TEMPLATES_GLOBALS_FRAGMENTS_DIR', THEME_TEMPLATES_GLOBALS_DIR . DSP . 'fragments');

define('THEME_URI', get_template_directory_uri());
define('THEME_DIST_URI', THEME_URI . '/dist/');
define('THEME_ASSETS_URI', THEME_URI . '/assets/');
define('THEME_ASSETS_STYLE_URI', THEME_ASSETS_URI . '/style/');
define('THEME_ASSETS_SCRIPT_URI', THEME_ASSETS_URI . '/script/');
define('THEME_SHORTCODES_URI', THEME_URI . '/templates/shortcodes/');
