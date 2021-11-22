<?php

/**
 *  Debugging tools
 *
 * @category   Functions
 * @version    1.0.0
 * @since      1.0.0
 */


/**
 *  Dump Die
 *
 * @param array $args
 *
 * @version    1.0.0
 * @since      1.0.0
 * @category
 */

if ( !function_exists('dd') ) {
	function dd (...$args) {
		var_dump(...$args);
		die();
	}
}