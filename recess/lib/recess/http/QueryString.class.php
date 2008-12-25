<?php
/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
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