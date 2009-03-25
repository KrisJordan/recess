<?php

/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class Formats {
	const XHTML = 'xhtml';
	const JSON = 'json';
	const XML = 'xml';
	const invalid = 'INVALID';
	
	public static $all = array(self::XHTML, self::JSON, self::XML);
}

?>