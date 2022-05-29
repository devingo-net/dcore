<?php

/**
 *  Blog Posts api
 *
 * @category    Api
 * @version     1.0.0
 * @since       1.0.0
 */

namespace DCore\Api;

use ElementorPro\Modules\QueryControl\Module as Module_Query;
use Exception;
use DCore\Api;
use DCore\Elementor\Modules\Page\Widgets\Blog_Posts as Blog_Posts_Widget;
use DCore\Helper;
use DCore\Theme;

class Blog_Posts extends Api {

	/**
	 * get paged posts
	 *
	 * @param array           $params
	 * @param \DCore\Api $core
	 */
	public function getPagedPosts (array $params, Api $core) : void {
		$validate = dcValidate($params, [
			'pageID'   => 'required|numeric',
			'widgetID' => 'required|string|min:1|max:20',
			'page'     => 'required|numeric'
		]);
		if ( $validate->fails() ) {
			self::responseWarning(dcValidationHTML($validate));
		}
        if (isset($params['tax']) && $params['tax'] !== false) {
            $taxonomyTemplateFinderQuery = new \WP_Query(array(
                'post_type' => 'elementor_library',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    '_elementor_data' => array(
                        'key' => '_elementor_data',
                        'value' => $params['widgetID'],
                        'compare' => 'LIKE'
                    )
                )
            ));
            if ($taxonomyTemplateFinderQuery->have_posts()) {
                $params['pageID'] = $taxonomyTemplateFinderQuery->get_posts()[0]->ID;
            }
        }
		$elementorMetaData = get_post_meta((int) $params['pageID'], '_elementor_data', true);
		if ( !$elementorMetaData ) {
			self::responseWarning(__('Page is not found!', THEME_TEXTDOMAIN));
		}
		try {
			$elementorMetaData = json_decode($elementorMetaData, true);
		} catch ( Exception $e ) {
			$elementorMetaData = [];
		}
		$widgetSettings = Helper::arraySearch($elementorMetaData, ['id' => $params['widgetID']]);
		$widgetSettings = reset($widgetSettings);
		if ( $widgetSettings === false || empty($widgetSettings) ) {
			self::responseWarning(__('Widget is not found!', THEME_TEXTDOMAIN));
		}

		try {
			$gridProductWidget = new Blog_Posts_Widget($widgetSettings, $widgetSettings);
		} catch ( \Exception $e ) {
			$gridProductWidget = null;
		}

		if ( $gridProductWidget === null ) {
			self::responseWarning(__('Widget is not found!', THEME_TEXTDOMAIN));
		}


		$widgetSettings = $gridProductWidget->get_settings_for_display();


		$elementorQuery                = Module_Query::instance();
		$widgetSettings['paginateKey'] = 'page_' . $gridProductWidget->get_id();
		$widgetSettings['currentPage'] = $params['page'];
		$postQuery                     = $elementorQuery->get_query($gridProductWidget, 'blogPosts', [
			'paged' => $widgetSettings['currentPage']
		]);
		$widgetSettings['totalPages']  = $postQuery->max_num_pages;

		if ( !isset($widgetSettings['cardStyle']) ) {
			return;
		}


		if ( isset($widgetSettings['cardStyle']) && $gridProductWidget->cardStyle !== false ) {
			$widgetSettings['cardStyle'] = THEME_TEMPLATES_DIR . DSP . 'globals' . DSP . 'cards' . DSP . $gridProductWidget->cardStyle . DSP . $widgetSettings['cardStyle'];
		}

		$widgetSettings['posts'] = [];

		if ( $postQuery->have_posts() ) {
			while ( $postQuery->have_posts() ) {
				$postQuery->the_post();
				global $post;
				ob_start();
				Theme::getTemplatePart($widgetSettings['cardStyle'], [
					'post' => $post
				], true);
				$widgetSettings['posts'][] = ob_get_clean();
			}
		}


		$result = ['content' => $widgetSettings['posts']];
		if ( $widgetSettings['paginateType'] === "number" ) {
			ob_start();
			Theme::getTemplatePart('globals/loop/pagination', [
				'paginateKey' => $widgetSettings['paginateKey'],
				'totalPages'  => $widgetSettings['totalPages'],
				'currentPage' => $widgetSettings['currentPage'],
				'midSize' => $widgetSettings['midSize'],
				'base' => wp_get_referer() . '%_%',
			]);
			$result['pagination'] = ob_get_clean();
		}

		self::responseSuccess([
			'data' => $result
		]);
	}
}