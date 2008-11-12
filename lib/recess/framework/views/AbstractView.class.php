<?php
Library::import('recess.http.Resposne');
Library::import('recess.lang.RecessClass');

/**
 * Renders a Response in a desired format by sending relevant
 * HTTP headers usually followed by a rendered body.
 * 
 * @author Kris Jordan
 * @abstract 
 */
abstract class AbstractView extends RecessClass {
	/**
	 * The entry point from the Coordinator with a Response to be rendered.
	 * Delegates the two steps in rendering a view: 1) Send Headers, 2) Render Body
	 *
	 * @param Response $response
	 */
	public final function respondWith(Response $response) {
		if(!headers_sent())
			$this->sendHeadersFor($response);
		
		if(ResponseCodes::canHaveBody($response->code) && !$response instanceof ForwardingResponse) {
			$this->render($response);
		}
		
	}
	
	/**
	 * Responsible for sending all headers in a Response. Marked final because
	 * all headers should be bundled in Response object.
	 *
	 * @param Response $response
	 * @final
	 */
	protected final function sendHeadersFor(Response $response) {
		
		header('HTTP/1.1 ' . ResponseCodes::getMessageForCode($response->code));
		
		foreach($response->headers as $header) {
			header($header);
		}
		
		foreach($response->getCookies() as $cookie) {
			setcookie($cookie->name, $cookie->value, $cookie->expire, $cookie->path, $cookie->domain, $cookie->secure, $cookie->httponly);
		}
		
		flush();

		// TODO: Determine other headers to send here. Content-Type, Caching, Etags, ...
	}
	
	/**
	 * Realizes HTTP's body content based on the Response parameter. Responsible
	 * for returning content in the format desired. The render method likely uses
	 * inversion of control which delegates to another method within the view to 
	 * realize the Response.
	 *
	 * @param Response $response
	 * @abstract 
	 */
	protected abstract function render(Response $response);
}
?>