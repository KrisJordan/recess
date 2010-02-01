<?php
/**
 * The inflector provides basic functionality for transforming words
 * between their singular and plural forms, as well as programmatic forms
 * (i.e. camelCapsFormat, ProperCapsFormat, under_scores_format)
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class Inflector {
	
	/**
	 * Return the plural form of an english word, in many cases.
	 * Currently this is naive and only appends an 's' to the end of a word.
	 * i.e. person => persons
	 *      thing => things
	 *      goose => gooses
	 *
	 * @param string $word
	 * @return string
	 */
	public static function toPlural($word) {
		return $word .= 's';
	}
	
	/**
	 * Return the singular form of an english word, in many cases.
	 * Currently this is naive and only removes the last character
	 * of a string.
	 * 
	 * i.e. persons => persons
	 * 		things => thing
	 * 		oxen => oxe
	 *
	 * @param string $word
	 * @return string
	 */
	public static function toSingular($word) {
		return substr($word, 0, -1);
	}
	
	/**
	 * Return whether or not an english word is plural. Currently
	 * naive and only returns whether or not the last character
	 * is an 's' or not.
	 *
	 * @param string $word
	 * @return string
	 */
	public static function isPlural($word) {
		return substr($word, -1, 1) === 's';
	}
	
	/**
	 * Go from underscores_form or camelCapsForm to ProperCapsForm.
	 * 
	 * i.e. this_is_in_underscores => ThisIsInUnderscores
	 * 		helloWorld => HelloWorld
	 * 
	 * @param string $word in camelCaps or underscores_form.
	 * @return string in ProperCapsForm
	 */
	public static function toProperCaps($word) {
		$word = explode('_', trim($word, '_'));
		$word = array_map('ucfirst', $word);
		return implode('', $word);
	}
	
	/**
	 * Go from underscores_form or ProperCapsForm to camelCapsForm
	 * 
	 * i.e. this_is_in_underscores => thisIsInUnderscores
	 * 		HelloWorld => helloWorld
	 *
	 * @param string $word in ProperCapsForm or underscores_form
	 * @return string camelCapsForm
	 */
	public static function toCamelCaps($word) {
		$word = self::toProperCaps($word);
		$word[0] = strtolower($word[0]);
		return $word;
	}
	
	/**
	 * Go from ProperCapsForm or camelCapsForm to underscores_form
	 *
	 * @param string $word in camelCapsForm or ProperCapsForm
	 * @return string underscores_form
	 */
	public static function toUnderscores($word) {
		return strtolower(
			preg_replace('/_+/', '_',
				trim(
					preg_replace(
						'/[A-Z]/',
						"_\\0",
						$word
					),
				'_')
			)
		);
	}
	
	/**
	 * Go from underscores_form or camelCapsForm or ProperCapsForm to English Form.
	 *
	 * @param string $word in underscores_form or camelCapsForm or ProperCapsForm
	 * @return string in English Form
	 */
	public static function toEnglish($word) {
		$word = Inflector::toUnderscores($word);
		$word = explode('_', $word);
		$word = array_map('ucfirst', $word);
		return implode(' ', $word);
	}
}

?>