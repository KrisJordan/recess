<?php

abstract class QueryString {
	// michael=scott&pam=beasley => array('michael'=>'scott', 'pam'=>'beasley')
	public static function parse($string) {
		$exploded = explode('&', $string); 
		$return_array  = array();
		
		foreach($exploded as $pair) { 
			$item = explode('=', $pair);
			if(count($item) == 2) {
				$return_array[urldecode($item[0])] = urldecode($item[1]);
			} 
		}
		
		return $return_array; 
	}
}

?>