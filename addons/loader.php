<?php
$manifest = require_once __DIR__ . '/manifest.php';
if ( is_array($manifest) && !empty($manifest) ) {
	foreach ( $manifest as $item ) {
		$loaderFile = __DIR__ . DIRECTORY_SEPARATOR . $item . DIRECTORY_SEPARATOR . 'loader.php';
		if ( !file_exists($loaderFile) ) {
			continue;
		}
		require_once $loaderFile;
	}
}