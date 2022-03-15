<?php

/**
 *
 *
 * @category
 * @version    1.0.0
 * @since      1.0.0
 */

namespace DCore;


use RuntimeException;
use WP_User;

/**
 * Class Api
 *
 * @package DCore
 */
class Api {
	public ?WP_User $user;

	public static function init () : void {
		$module = $_POST['module'] ?? '';
		$method = $_POST['method'] ?? '';
		$nonce  = $_POST['nonce'] ?? '';

		if ( !wp_doing_ajax() || ($module !== 'Product_Carousel' && !wp_verify_nonce($nonce, 'ajax-nonce')) ) {
			self::responseError(__('Direct access is blocked!', THEME_TEXTDOMAIN));
		}
		if ( empty($module) || empty($method) ) {
			self::responseError(__('Module/Method name is required!', THEME_TEXTDOMAIN));
		}
		$api = new self();
		if ( is_user_logged_in() ) {
			$api->user = wp_get_current_user();
		}
		$api->callRequest($module, $method, $_POST);
	}

	/**
	 * response core
	 *
	 * @param        $data
	 * @param int    $code
	 * @param string $type
	 */
	public static function response ($data, int $code, string $type = 'success') : void {
		$response = [
			'status' => true,
			'data'   => '',
			'notify' => true,
			'code'   => $code
		];
		if ( is_array($data) ) {
			$response = array_merge($response, $data);
		}
		if ( is_string($data) ) {
			$response['data'] = $data;
		}

		if ( $type === 'success' ) {
			wp_send_json_success($response, $code);
		}
		wp_send_json_error($response, $code);
	}

	/**
	 * response as success
	 *
	 * @param     $data
	 * @param int $code
	 */
	public static function responseSuccess ($data, int $code = 200) : void {
		self::response($data, $code);
	}

	/**
	 * response as error
	 *
	 * @param     $data
	 * @param int $code
	 */
	public static function responseError ($data, int $code = 500) : void {
		self::response($data, $code, 'error');
	}

	/**
	 * response as warning
	 *
	 * @param     $data
	 * @param int $code
	 */
	public static function responseWarning ($data, int $code = 400) : void {
		self::response($data, $code, 'warning');
	}


	/**
	 * Call Method
	 *
	 * @param string $module
	 * @param string $method
	 * @param array  $params
	 *
	 * @return void
	 */
	public function callRequest (string $module, string $method, array $params = []) : void {
		$moduleName = self::class . '\\' . $module;

		if ( !class_exists($moduleName) ) {
			self::response(__('Module not exists!', THEME_TEXTDOMAIN), 500, 'failed');
		}

		$moduleClass = new $moduleName();

		if ( !method_exists($moduleClass, $method) ) {
			self::response(__('Method not exists!', THEME_TEXTDOMAIN), 500, 'failed');
		}

		if ( WP_DEBUG ) {
			$moduleClass->$method($params, $this);
		} else {
			try {
				$moduleClass->$method($params, $this);
			} catch ( RuntimeException $e ) {
				self::response(__('Error when processing!', THEME_TEXTDOMAIN), 500, 'failed');
			}
		}
	}
}