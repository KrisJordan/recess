<?php

Library::import('recess.http.Request');
Library::import('recess.http.Formats');
Library::import('recess.http.QueryString');
Library::import('recess.http.Methods');

class Environment {

	public static function getRawRequest() {
		$request = new Request();
		
		$request->method = $_SERVER['REQUEST_METHOD'];
		
		$request->format = Formats::XHTML;
		
		$request->setResource(self::stripQueryString($_SERVER['REQUEST_URI']));
		
		$request->get = $_GET;
		
		$request->post = $_POST;
		
		if($request->method == Methods::PUT) {
			$request->put = self::getPutParameters();
		}
		
		$request->headers = self::getHttpRequestHeaders();
		
		$request->username = @$_SERVER['PHP_AUTH_USER'];
		
		$request->password = @$_SERVER['PHP_AUTH_PW'];
		
		$request->cookie = $_COOKIE;
		
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
	
	private static function getPutParameters() {
		$putdata = file_get_contents('php://input');
		return QueryString::parse($putdata);
	}
}

?>