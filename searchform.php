<?php
/**
 * The searchform.php template.
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */


$ariaLabel = !empty($args['label']) ? 'aria-label="' . esc_attr($args['label']) . '"' : '';
?>
<form role="search" <?php echo $ariaLabel; ?> method="get" class="search-form"
      action="<?php echo esc_url(home_url('/')); ?>">
    <div class="uk-inline">
        <span class="uk-form-icon" uk-icon="icon: search"></span>
        <input type="search" class="uk-input search-field"
               placeholder="<?php _e('Search &hellip;', THEME_TEXTDOMAIN) ?>" value="<?php echo get_search_query();
	    ?>" name="s"/>
    </div>
</form>
