<?php
/**
 * Hook which related to wordpress query
 *
 * @category   Hooks
 * @version    1.0.0
 * @since      1.0.0
 */

/**
 * pre get posts customizes
 *
 * @param $query
 */
function dcPreGetPosts($query)
{
    if (!is_admin() && !is_singular()) {
        $query->set('post_status', 'publish');
    }
}

add_action('pre_get_posts', 'dcPreGetPosts');