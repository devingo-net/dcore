<?php

/**
 *
 *
 * @category
 * @version    1.0.0
 * @since      1.0.0
 */

use DCore\Api;

add_action('wp_ajax_themeApiAjax', [Api::class, 'init']);
add_action('wp_ajax_nopriv_themeApiAjax', [Api::class, 'init']);