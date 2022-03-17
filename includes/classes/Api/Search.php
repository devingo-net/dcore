<?php

/**
 *
 *
 * @category
 * @version    1.0.0
 * @since      1.0.0
 */

namespace DCore\Api;

use DCore\Api;
use DCore\Helper;
use WP_Query;

class Search extends Api {

	public function getResult (array $params, Api $core) : void {
		$validate = dcValidate($params, [
			'search'   => 'required|min:3|max:100',
			'category' => 'max:100',
			'type'     => 'string|in:all,product,post',
			'count'    => 'numeric|min:1|max:20',
			'result'   => 'string|in:both,tax,post'
		]);

		if ( $validate->fails() ) {
			self::responseWarning(dcValidationHTML($validate));
		}

		$postType      = $params['type'] ?? 'post';
		$category      = $params['category'] ?? 'all';
		$searchResults = [];
		$count         = $params['count'] ?? 10;
		$result        = $params['result'] ?? 'both';
		$taxonomyCount = $result === 'both' ? round($count / 3) : $count;
		$taxonomyCount = $postType === 'all' ? round($taxonomyCount / 2) : $taxonomyCount;


		if ( $result !== 'post' && $category === 'all' ) {

			if ( $postType !== 'product' ) {
				$searchResults = array_slice($this->taxonomySearch('category', $params['search']), 0, $taxonomyCount);
			}

			if ( $postType !== 'post' ) {
				$searchResults = array_merge($searchResults, array_slice($this->taxonomySearch('product_cat', $params['search']), 0, $taxonomyCount));
			}

		}

		$count -= count($searchResults);

		if ( $result === 'tax' || $count < 0 ) {
			self::responseSuccess([
				'data' => [
					'items' => $searchResults,
					'count' => count($searchResults)
				]
			]);
		}

		$postType = $postType === 'all' ? ['post', 'product'] : $postType;

		$searchArgs = [
			'post_type'      => $postType,
			's'              => $params['search'],
			'posts_per_page' => $count,
            'post_status'   =>  'publish'
		];

		$query = new WP_Query($searchArgs);

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$searchResults[] = [
					'type'     => get_post_type(),
					'title'    => get_the_title(),
					'subtitle' => dcGetPostSubTitle(get_the_ID()),
					'link'     => get_the_permalink(),
					'image'    => get_the_post_thumbnail_url(null,'thumbnail')
				];
			}
		}

		wp_reset_postdata();

		self::responseSuccess([
			'data' => [
				'items' => $searchResults,
				'count' => count($searchResults)
			]
		]);
	}

	private function taxonomySearch (string $taxonomy, string $search) : array {
		if ( empty($search) ) {
			return [];
		}
		$categories = get_terms([
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'name__like' => $search
		]);
		if ( empty($categories) ) {
			return [];
		}
		$taxonomyDetails = get_taxonomy($taxonomy);
		$result          = [];
		foreach ( $categories as $category ) {
			$termImage = get_term_meta($category->term_id, 'thumbnail_id', true);
			$termImage = !empty($termImage) ? wp_get_attachment_image_src($termImage) : false;
			$termImage = is_array($termImage) ? $termImage[0] : false;
			$result[]  = [
				'type'     => 'cat',
				'tax'      => $taxonomy,
				'title'    => $category->name,
				'subtitle' => $taxonomyDetails->labels->name,
				'link'     => get_term_link($category),
				'image'    => $termImage
			];
		}

		return $result;
	}
}