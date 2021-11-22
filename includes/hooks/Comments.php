<?php

/**
 * comment hooks
 *
 * @category   Hooks
 * @version    1.0.0
 * @since      1.0.0
 */

use DCore\Features\Advanced_Reviews;

/**
 * edit comment form fields
 *
 * @param array $args
 *
 * @return array
 */
function dcCommentFormFields (array $args) : array {

	if ( isset($args['fields']['author']) ) {
		$args['fields']['author'] = str_replace('id="author"', 'id="author" class="uk-input"',
			$args['fields']['author']);
	}
	if ( isset($args['fields']['email']) ) {
		$args['fields']['email'] = str_replace('id="email"', 'id="email" class="uk-input"', $args['fields']['email']);
	}
	if ( isset($args['fields']['url']) ) {
		$args['fields']['url'] = str_replace('id="url"', 'id="url" class="uk-input"', $args['fields']['url']);
	}

	if ( isset($args['comment_field']) ) {
		$args['comment_field'] = str_replace('id="comment"', 'id="comment" class="uk-textarea"',
			$args['comment_field']);
	}
	if ( isset($args['class_submit']) ) {
		$args['class_submit'] = 'submit button button-primary';
	}
	if ( isset($args['fields']['cookies']) ) {
		$args['fields']['cookies'] = str_replace('id="wp-comment-cookies-consent"', 'id="wp-comment-cookies-consent" class="uk-checkbox"',
			$args['fields']['cookies']);
	}

	return $args;
}

add_filter('comment_form_defaults', 'dcCommentFormFields');
Advanced_Reviews::init();