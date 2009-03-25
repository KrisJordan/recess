<?php
Library::import('recess.http.Request');
Library::import('recess.http.Formats');
Library::import('recess.http.Methods');

/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @contributor Luiz Alberto Zaiats
 * 
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class Environment {

	public static function getRawRequest() {
		$request = new Request();
		
		$request->method = $_SERVER['REQUEST_METHOD'];
		
		$request->format = Formats::XHTML;
		
		$request->setResource(self::stripQueryString($_SERVER['REQUEST_URI']));
		
		$request->get = $_GET;
		
		$request->post = $_POST;
		
		if(	$request->method == Methods::POST ||
			$request->method == Methods::PUT )
		{
			$request->input = file_get_contents('php://input');

			if($request->method == Methods::POST) {
				$request->post = $_POST;
			} else {
				$request->put = self::getPutParameters($request->input);
			}

		}
		
		$request->headers = self::getHttpRequestHeaders();
		
		$request->username = @$_SERVER['PHP_AUTH_USER'];
		
		$request->password = @$_SERVER['PHP_AUTH_PW'];
		
		$request->cookies = $_COOKIE;
		
		// TODO: isAjax?, from django
		// def is_ajax(self):
        //  return self.META.get('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest'
		
		return $request;
	}
	
	private static function	stripQueryString($uri) {
		$questionMarkPosition = strpos($uri, '?');
		if($questionMarkPosition !== false) {
			return substr($uri,0,$questionMarkPosition);
		}
		return $uri;
	}
	
	private static function getHttpRequestHeaders() {
		$lengthOfHTTP_ = 5;
		$httpHeaders = array();
		
		foreach(array_keys($_SERVER) as $key) {
			if(substr($key,0,$lengthOfHTTP_) == 'HTTP_') {
				$httpHeaders[substr($key, $lengthOfHTTP_)] = $_SERVER[$key];
			}
		}
		return $httpHeaders;
	}
	
	private static function getPutParameters($input) {
		$putdata = $input;
		if(function_exists('mb_parse_str')) {
	    	mb_parse_str($putdata, $outputdata);
		} else {
			parse_str($putdata, $outputdata);
		}
    	return $outputdata;
	}
}

?>