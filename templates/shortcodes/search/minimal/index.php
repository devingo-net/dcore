<?php

/**
 *  search widget shortcode
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

/**
 * Style Name: Minimal Design
 * Disable Options: filter_settings
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

use Elementor\Icons_Manager;

/**
 * @var string  $widgetID
 * @var array   $buttonIcon
 * @var array   $categoriesList
 * @var string  $fieldPlaceholder
 * @var string  $buttonType
 * @var string  $buttonText
 * @var string  $postType
 * @var integer $resultCount
 * @var string  $resultType
 */

?>

<form action="<?= site_url() ?>" class="search-form" id="search-form-<?= $widgetID ?>">
    <input type="hidden" name="post_type" class="search-post-type" value="<?= esc_attr($postType) ?>">
    <input type="hidden" class="search-count" value="<?= esc_attr($resultCount) ?>">
    <input type="hidden" class="search-result-type" value="<?= esc_attr($resultType) ?>">

    <div class="uk-flex">
        <div class="uk-width-expand uk-position-relative">
            <input type="search" name="s" class="uk-input uk-width-1-1 uk-form-width-medium search-field"
                   placeholder="<?= $fieldPlaceholder ?>"
                   value="<?= get_query_var('s') ?>" autocomplete="off">
            <div class="uk-position-small uk-position-center-left">
            <div class="uk-hidden" uk-spinner></div>
            <button type="submit" class="search-button">
		        <?php
		        if ( $buttonType === 'text' ) {
			        echo $buttonText;
		        } else {
			        Icons_Manager::render_icon($buttonIcon, ['aria-hidden' => 'true']);
		        }
		        ?>
            </button>
            </div>
        </div>
    </div>
</form>
