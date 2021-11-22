<?php
/**
 * @param $query
 */
function dcPreGetPosts($query)
{
    if (!is_admin() && !is_singular()) {
        $query->set('post_status', 'publish');
    }
}

add_action('pre_get_posts', 'dcPreGetPosts');