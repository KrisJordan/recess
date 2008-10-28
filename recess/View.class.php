<?php

Library::import('recess.http.Resposne');

abstract class View {
	
	public final function respondWith(Response $response) {
		
		$this->sendHeadersFor($response);
		
		if(ResponseCodes::canHaveBody($response->code)) {
			$this->render($response);
		}
		
	}
	
	public final function sendHeadersFor(Response $response) {
		header('HTTP/1.1 ' . ResponseCodes::getMessageForCode($response->code));
		
		foreach($response->headers as $header) {
			header($header);
		}
		
		foreach($response->getCookies() as $cookie) {
			setcookie($cookie->name, $cookie->value, $cookie->expire, $cookie->path, $cookie->domain, $cookie->secure, $cookie->httponly);
		}
		
		// TODO: Determine other headers to send here. Content-Type? Caching? Etags?
	}
	
	public abstract function render(Response $response);
	
}

?>