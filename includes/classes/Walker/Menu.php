<?php

/**
 *
 *
 * @category
 * @version    1.0.0
 * @since      1.0.0
 */

namespace DCore\Walker;


use ElementorPro\Modules\ThemeBuilder\Module;
use DCore\Elementor\Advanced_Locations;

class Menu extends \Walker_Nav_Menu {
	// Tell Walker where to inherit it's parent and id values
	var $db_fields = [
		'parent' => 'menu_item_parent',
		'id'     => 'db_id'
	];

	/**
	 * At the start of each element, output a <li> and <a> tag structure.
	 *
	 * Note: Menu objects include url and title properties, so we will use those.
	 *
	 * @param       $output
	 * @param       $item
	 * @param int   $depth
	 * @param array $args
	 * @param int   $id
	 */
	public function start_el (&$output, $item, $depth = 0, $args = [], $id = 0) : void {
		$itemType = get_post_meta($item->ID, 'menu-type', true);
		if ( empty($itemType) ) {
			$output = '<li>';

			return;
		}

		if ( $depth > 0 ) {
			if ( isset($args->link_before) ) {
				$args->link_before = '';
			}
			parent::start_el($output, $item, $depth, $args, $id);

			return;
		}
		$item->classes = empty($item->classes) ? [] : (array) $item->classes;

		$itemIcon                   = get_post_meta($item->ID, 'menu-icon', true);
		$itemType                   = empty($itemType) ? 'normal' : $itemType;
		$itemTypeElementorItemWidth = get_post_meta($item->ID, 'menu-type-elementor-width', true);
		$itemTypeElementorItem      = get_post_meta($item->ID, 'menu-type-elementor-item', true);

		$item->classes[] = 'menu-design-type-' . esc_attr($itemType);

		if ( $itemType === 'elementor' ) {
			$item->classes[] = 'menu-elementor-width-' . esc_attr($itemTypeElementorItemWidth);
		}

		if ( isset($args->link_before) ) {
			$args->link_before = '';
		}
		if ( isset($args->link_before) && !empty($itemIcon) && is_numeric($itemIcon) ) {
			$args->link_before = sprintf('<i class="menu-icon" style="background-image: url(%s)"></i>',
				esc_attr(wp_get_attachment_url($itemIcon)));
		}


		parent::start_el($output, $item, $depth, $args, $id);

		if ( $itemType === 'elementor' ) {
			$document = new Advanced_Locations();
			ob_start();
			if ( !empty($itemTypeElementorItem) && get_post_type($itemTypeElementorItem) === "elementor_library" ) {
				$document->doLocationByID('menu', $itemTypeElementorItem);
			}
			$output .= ob_get_clean();
			$output = str_replace('class="elementor elementor-' . $itemTypeElementorItem,
				'class="elementor elementor-' . $itemTypeElementorItem . ' sub-menu ', $output);
		}
	}


}