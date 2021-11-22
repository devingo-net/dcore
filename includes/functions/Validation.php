<?php

/**
 *
 *
 * @category   Functions
 * @version    1.0.0
 * @since      1.0.0
 */

use Illuminate\Validation;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation;
use Illuminate\Validation\Validator;

/**
 * Get Validator Translator Object
 *
 * @return \Illuminate\Translation\Translator
 */
function dcGetValidatorTranslator () : Translation\Translator {
	$fileSystem = new Filesystem();
	$fileLoader = new Translation\FileLoader($fileSystem, THEME_LANGS_DIR  . DSP . 'validation');

	return new Translation\Translator($fileLoader, 'fa-IR');
}

/**
 * Get Validator Object
 *
 * @return \Illuminate\Validation\Factory
 */
function dcGetValidator () : Validation\Factory {
	$translator = dcGetValidatorTranslator();

	return new Validation\Factory($translator);
}

/**
 * Validate Data by Specified Rules
 *
 * @param array $data
 * @param array $rules
 *
 * @return Validator
 */
function dcValidate (array $data = [], array $rules = []) : Validator {
	$validator = dcGetValidator();

	return $validator->make($data, $rules);
}

/**
 * Get Errors Descriptions from Validator
 *
 * @param $validator
 *
 * @return string
 */
function dcValidationHTML ($validator) : string {
	$result = '';

	if ( $validator instanceof Validator ) {
		$errors = $validator->errors()->all();

		if ( !empty($errors) ) {
			foreach ( $errors as $error ) {
				$result .= $error . PHP_EOL . '<br>';
			}
		}
	}

	return $result;
}