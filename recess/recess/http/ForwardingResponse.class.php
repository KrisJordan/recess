<?php
Library::import('recess.http.Response');

/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
abstract class ForwardingResponse extends Response {
	
	public $forwardUri;
	
	public function __construct(Request $request, $code, $forwardUri) {
		parent::__construct($request, $code);
		$this->forwardUri = $forwardUri;
	}
	
}
?>