<?php

/**
 *  Meta box hooks
 *
 * @category    Hooks
 * @version     1.0.0
 * @since       1.0.0
 */

use Elementor\TemplateLibrary\Source_Local;


/**
 * add meta box custom locations
 */
function dcAddMetaBoxAfterTitleLocation () {
	global $post, $wp_meta_boxes;
	do_meta_boxes(get_current_screen(), 'afterTitle', $post);
	unset($wp_meta_boxes['post']['afterTitle']);
}

/**
 * add meta boxes
 */
function dcAddMetaBoxes () {
	add_meta_box('sub-title', __('Sub title', THEME_TEXTDOMAIN), 'dcSubTitleMetaBoxCallback', [
		'post',
		'product'
	], 'afterTitle', 'high');
}

/**
 * sub title meta box html
 *
 * @param $post
 */
function dcSubTitleMetaBoxCallback ($post) {
	$subtitleMetaValue = get_post_meta($post->ID, 'sub-title', true);

	echo '<input type="text" id="sub-title-field" placeholder="' . __('Sub title', THEME_TEXTDOMAIN) . '" name="sub-title"
           value="' . $subtitleMetaValue . '">';

}

/**
 * save meta box data
 *
 * @param $post_id
 */
function dcMetaBoxSave ($post_id) {
	if ( isset($_POST['sub-title']) ) {
		update_post_meta($post_id, 'sub-title', $_POST['sub-title']);
	}
}

/**
 * add admin nav menu custom meta box
 *
 * @param $item_id
 * @param $item
 * @param $depth
 * @param $args
 * @param $id
 */
function dcNavMenuCustomFields ($item_id, $item, $depth, $args, $id) {
	$templateItems  = [];
	$themeLibraries = new Source_Local();
	if ( !empty($themeLibraries->get_items(['type' => 'menu'])) ) {
		foreach ( $themeLibraries->get_items(['type' => 'menu']) as $libraryItem ) {
			$templateItems[$libraryItem['template_id']] = $libraryItem['title'];
		}
	}

	$itemIcon                   = get_post_meta($item_id, 'menu-icon', true);
	$itemType                   = get_post_meta($item_id, 'menu-type', true);
	$itemTypeElementorItemWidth = get_post_meta($item_id, 'menu-type-elementor-width', true);
	$itemTypeElementorItem      = get_post_meta($item_id, 'menu-type-elementor-item', true);

	DCore\Metabox::addControl('menu-icon', [
		'type'  => 'imageUpload',
		'label' => __('Icon', THEME_TEXTDOMAIN),
		'value' => $itemIcon,
		'name'  => 'menu-icon[' . $item_id . ']'
	]);


	DCore\Metabox::addControl('menu-type', [
		'type'    => 'select',
		'label'   => __('Type', THEME_TEXTDOMAIN),
		'value'   => $itemType,
		'default' => 'normal',
		'name'    => 'menu-type[' . $item_id . ']',
		'options' => [
			'normal'    => __('Normal', THEME_TEXTDOMAIN),
			'elementor' => __('Elementor', THEME_TEXTDOMAIN),
		]
	]);
	DCore\Metabox::addControl('menu-type-elementor-width', [
		'type'      => 'select',
		'label'     => __('Width', THEME_TEXTDOMAIN),
		'value'     => $itemTypeElementorItemWidth,
		'default'   => 'boxed',
		'name'      => 'menu-type-elementor-width[' . $item_id . ']',
		'options'   => [
			'boxed'     => __('Boxed', THEME_TEXTDOMAIN),
			'fullwidth' => __('Full Width', THEME_TEXTDOMAIN),
		],
		'condition' => [
			'menu-type[' . $item_id . ']' => 'elementor'
		]
	]);
	DCore\Metabox::addControl('menu-type-elementor-item', [
		'type'      => 'select',
		'label'     => __('Template', THEME_TEXTDOMAIN),
		'value'     => $itemTypeElementorItem,
		'name'      => 'menu-type-elementor-item[' . $item_id . ']',
		'options'   => $templateItems,
		'condition' => [
			'menu-type[' . $item_id . ']' => 'elementor'
		]
	]);

}

/**
 * save admin nav menu custom meta box
 *
 * @param $menu_id
 * @param $menu_item_db_id
 * @param $menu_item_args
 */
function dcNavMenuCustomFieldsUpdate ($menu_id, $menu_item_db_id, $menu_item_args) {
	if ( defined('DOING_AJAX') && DOING_AJAX ) {
		return;
	}

	check_admin_referer('update-nav_menu', 'update-nav-menu-nonce');

	if ( isset($_POST['menu-icon']) && !empty($_POST['menu-icon']) ) {

		$value = $_POST['menu-icon'][$menu_item_db_id];
		if ( !empty($value) ) {
			update_post_meta($menu_item_db_id, 'menu-icon', $value);
		} else {
			delete_post_meta($menu_item_db_id, 'menu-icon');
		}
	}

	if ( isset($_POST['menu-type']) && !empty($_POST['menu-type']) ) {

		$value = $_POST['menu-type'][$menu_item_db_id];
		if ( !empty($value) ) {
			update_post_meta($menu_item_db_id, 'menu-type', $value);
		} else {
			delete_post_meta($menu_item_db_id, 'menu-type');
		}
	}

	if ( isset($_POST['menu-type-elementor-item']) && !empty($_POST['menu-type-elementor-item']) ) {

		$value = $_POST['menu-type-elementor-item'][$menu_item_db_id];
		if ( !empty($value) ) {
			update_post_meta($menu_item_db_id, 'menu-type-elementor-item', $value);
		} else {
			delete_post_meta($menu_item_db_id, 'menu-type-elementor-item');
		}
	}

	if ( isset($_POST['menu-type-elementor-width']) && !empty($_POST['menu-type-elementor-width']) ) {

		$value = $_POST['menu-type-elementor-width'][$menu_item_db_id];
		if ( !empty($value) ) {
			update_post_meta($menu_item_db_id, 'menu-type-elementor-width', $value);
		} else {
			delete_post_meta($menu_item_db_id, 'menu-type-elementor-width');
		}
	}
}


function dcProductAttributesMetaBoxes ($attribute, $i) {
	if ( !isset($_POST['post_id']) ) {
		global $post;
		$_POST['post_id'] = $post->ID ?? 0;
	}
	$isFeaturedValue = get_post_meta($_POST['post_id'], $attribute->get_name() . "_isFeatured", true);
	$groupValue = get_post_meta($_POST['post_id'], $attribute->get_name() . "_group", true);
	?>
    <tr>
        <td>
			<?php
			DCore\Metabox::addControl('isFeatured', [
				'type'           => 'checkbox',
				'checkbox_label' => __('Featured attribute', THEME_TEXTDOMAIN),
				'value'          => $isFeaturedValue,
				'name'           => 'attribute_isFeatured[' . $i . ']'
			]);
			?>
        </td>
    </tr>
    <tr>
        <td>
			<?php
			DCore\Metabox::addControl('group', [
				'type'           => 'text',
				'label' => __('Group name', THEME_TEXTDOMAIN),
				'value'          => $groupValue,
				'name'           => 'attribute_group[' . $i . ']'
			]);
			?>
        </td>
    </tr>
	<?php
}

function dcProductAttributesMetaBoxSave () {
	check_ajax_referer('save-attributes', 'security');
	parse_str($_POST['data'], $data);

	$postID = absint($_POST['post_id']);
	if ( array_key_exists("attribute_names", $data) && is_array($data["attribute_names"]) ) {
		foreach ( $data["attribute_names"] as $i => $val ) {

		    $featuredValue = $data["attribute_isFeatured"][$i] ?? 'off';
			update_post_meta($postID, $val . "_isFeatured", $featuredValue);

		    $groupValue = $data["attribute_group"][$i] ?? '';
			update_post_meta($postID, $val . "_group", $groupValue);

		}
	}
}


add_action('edit_form_after_title', 'dcAddMetaBoxAfterTitleLocation');
add_action('add_meta_boxes', 'dcAddMetaBoxes');
add_action('save_post', 'dcMetaBoxSave');
add_action('wp_nav_menu_item_custom_fields', 'dcNavMenuCustomFields', 10, 5);
add_action('wp_update_nav_menu_item', 'dcNavMenuCustomFieldsUpdate', 10, 5);
add_action('woocommerce_after_product_attribute_settings', 'dcProductAttributesMetaBoxes', 10, 2);
add_action('wp_ajax_woocommerce_save_attributes', 'dcProductAttributesMetaBoxSave', 0);