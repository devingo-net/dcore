<?php
/**
 * Dcore updater hooks
 *
 * @package DCore
 * @since 1.0.8
 */

function dcore_hooks_updater_elementor_menu_templates()
{
    if (get_option('dc_updater_elementor_menu_templates', false) === true) {
        return;
    }
    global $wpdb;
    if (!$wpdb) {
        return;
    }
    $results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = '_elementor_location' and meta_value = 'menu'", ARRAY_A);
    if (empty($results)) {
        return;
    }

    foreach ($results as $item) {
        $removed = $wpdb->delete($wpdb->postmeta, array('meta_key' => '_elementor_location', 'post_id' => $item['post_id']));

        if ($removed != false) {
            $wpdb->update($wpdb->postmeta, [
                'meta_value' => 'menu'
            ], [
                'meta_key' => '_elementor_template_type',
                'post_id' => $item['post_id']
            ]);
        }
    }

    update_option('dc_updater_elementor_menu_templates', true);
}

add_action('init', 'dcore_hooks_updater_elementor_menu_templates');