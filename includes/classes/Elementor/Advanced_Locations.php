<?php

/**
 *
 *
 * @category
 * @version    1.0.0
 * @since      1.0.0
 */

namespace DCore\Elementor;


use ElementorPro\Core\Utils;
use ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager;
use ElementorPro\Modules\ThemeBuilder\Documents\Theme_Document;
use ElementorPro\Modules\ThemeBuilder\Module;

class Advanced_Locations extends Locations_Manager {

	/**
	 * @param $location
	 * @param $itemID
	 *
	 * @return bool
	 */
	public function doLocationByID ($location, $itemID) : ?bool {
		/** @var Theme_Document[] $documents_by_conditions */
		$documents_by_conditions = Module::instance()->get_conditions_manager()->get_documents_for_location($location);

		$this->add_doc_to_location($location, $itemID);

		// Locations Queue can contain documents that added manually.
		if ( empty($this->locations_queue[$location]) ) {
			return false;
		}

		/**
		 * Before location content printed.
		 *
		 * Fires before Elementor location was printed.
		 *
		 * The dynamic portion of the hook name, `$location`, refers to the location name.
		 *
		 * @param Locations_Manager $this An instance of locations manager.
		 *
		 * @since 2.0.0
		 *
		 */
		do_action("elementor/theme/before_do_{$location}", $this);


		$document = Module::instance()->get_document($itemID);


		// `$documents_by_conditions` can pe current post even if it's a draft.
		if ( empty($documents_by_conditions[$itemID]) ) {

			$post_status = get_post_status($itemID);

			if ( 'publish' !== $post_status ) {
				$this->inspector_log([
					'location'    => $location,
					'document'    => $document,
					'description' => 'Added manually but skipped because is not Published',
				]);

				$this->skip_doc_in_location($location, $itemID);
			}
		}

		$this->inspector_log([
			'location'    => $location,
			'document'    => $document,
			'description' => isset($documents_by_conditions[$itemID]) ? 'Added By Condition' : 'Added Manually',

		]);

		$this->current_location = $location;
		$document->print_content();
		$this->did_locations[]  = $this->current_location;
		$this->current_location = null;

		$this->set_is_printed($location, $itemID);


		/**
		 * After location content printed.
		 *
		 * Fires after Elementor location was printed.
		 *
		 * The dynamic portion of the hook name, `$location`, refers to the location name.
		 *
		 * @param Locations_Manager $this An instance of locations manager.
		 *
		 * @since 2.0.0
		 *
		 */
		do_action("elementor/theme/after_do_{$location}", $this);

		return true;
	}
}