<?php

namespace DCore\Elementor;
if (!class_exists("\ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager")) {
    class Advanced_Locations
    {
    }

    return;
}

/**
 * Class Advanced_Locations
 * @package DCore\Elementor
 */
class Advanced_Locations extends \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager
{

    /**
     * @param $location
     * @param $itemID
     *
     * @return bool
     */
    public function doLocationByID($location, $itemID): ?bool
    {
        $documents_by_conditions = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location($location);

        $this->add_doc_to_location($location, $itemID);

        // Locations Queue can contain documents that added manually.
        if (empty($this->locations_queue[$location])) {
            return false;
        }

        /**
         * Before location content printed.
         *
         * Fires before Elementor location was printed.
         *
         * The dynamic portion of the hook name, `$location`, refers to the location name.
         *
         * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $this An instance of locations manager.
         *
         * @since 2.0.0
         *
         */
        do_action("elementor/theme/before_do_{$location}", $this);


        $document = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_document($itemID);


        // `$documents_by_conditions` can pe current post even if it's a draft.
        if (empty($documents_by_conditions[$itemID])) {

            $post_status = get_post_status($itemID);

            if ('publish' !== $post_status) {
                $this->inspector_log([
                    'location' => $location,
                    'document' => $document,
                    'description' => 'Added manually but skipped because is not Published',
                ]);

                $this->skip_doc_in_location($location, $itemID);
            }
        }

        $this->inspector_log([
            'location' => $location,
            'document' => $document,
            'description' => isset($documents_by_conditions[$itemID]) ? 'Added By Condition' : 'Added Manually',

        ]);

        $this->current_location = $location;
        $document->print_content();
        $this->did_locations[] = $this->current_location;
        $this->current_location = null;

        $this->set_is_printed($location, $itemID);


        /**
         * After location content printed.
         *
         * Fires after Elementor location was printed.
         *
         * The dynamic portion of the hook name, `$location`, refers to the location name.
         *
         * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $this An instance of locations manager.
         *
         * @since 2.0.0
         *
         */
        do_action("elementor/theme/after_do_{$location}", $this);

        return true;
    }
}