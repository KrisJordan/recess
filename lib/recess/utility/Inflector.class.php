<?php

class Inflector {
	public static function toPlural($word) {
		return $word .= 's';
	}
	
	public static function toSingular($word) {
		return substr($word, 0, -1);
	}
	
	public static function isPlural($word) {
		return substr($word, -1, 1) === 's';
	}
	
	public static function toProperCaps($word) {
		return ucfirst(self::toCamelCaps($word));
	}
	
	public static function toCamelCaps($word) {
		return preg_replace('/_([a-z])/', 'ucfirst(${1})', $word);
	}
	
	public static function toEnglish($word) {
		$word = ucfirst($word);
		for($i = 0; $i < strlen($word) ; $i++) {
			if($word[$i] == '_') {
				$word[$i+1] = strtoupper($word[$i+1]);
			}
		}
		return str_replace('_', ' ', $word);
	}
	
	public static function toUnderscores($word) {
		$word[0] = strtolower($word[0]);
		$word = preg_replace('/([A-Z])/', 'strtolower(${1})_', $word);
		return $word;
	}
}

?>