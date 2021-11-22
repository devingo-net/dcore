<?php


namespace DCore\Elementor\Modules;

use DCore\Helper;
use ElementorPro\Base\Module_Base as Elementor_Module_Base;
use ReflectionClass;

abstract class Module_Base extends Elementor_Module_Base {
	/**
	 * @return array
	 */
	public function get_widgets () : array {
		try {
			$reflector = new ReflectionClass(get_class($this));
		} catch ( \ReflectionException $e ) {
			return [];
		}
		$reflector = dirname($reflector->getFileName()) . DIRECTORY_SEPARATOR;

		if ( !file_exists($reflector . 'Widgets') ) {
			return [];
		}

		$files = Helper::getDirFiles($reflector . 'Widgets');

		if ( empty($files) ) {
			return [];
		}

		foreach ( $files as &$file ) {
			$file = str_replace(['.php', '.PHP'], '', $file);
		}
		unset($file);

		return $files;
	}
}