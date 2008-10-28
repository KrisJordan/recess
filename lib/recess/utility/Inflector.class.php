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
		for($i = 0; $i < strlen($word) ; $i++) {
			if($word[$i] == '_') {
				$word[$i+1] = strtoupper($word[$i+1]);
			}
		}
		return str_replace('_', '', $word);
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
		$UPPER_A = ord('A');
		$UPPER_Z = ord('Z');
		for($i = 0; $i < strlen($word) ; $i++) {
			$ord = ord($word[$i]);
			if($ord >= $UPPER_A &&
				 $ord <= $UPPER_Z) {
				 	
				 if($i == 0) { $word[$i] = strtolower($word[$i]); continue; }
				 	
				 $word[$i] = strtolower($word[$i]);
				 $word = substr_replace($word, '_' . $word[$i], $i, 1);
				 ++$i;
			}
		}
		return $word;
	}
}

?>