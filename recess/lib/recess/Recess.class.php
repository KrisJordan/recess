<?php
Library::import('recess.http.ForwardingResponse');
/**
 * Entry into Recess PHP Framework occurs in the coordinator. It is responsible
 * for the flow of control from preprocessing of request data, the serving of a request
 * in a controller, and rendering a response to the request through a view.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @final 
 */
final class Recess {
	/**
	 * Recess PHP Framework Entry Point
	 * @param Request $request The raw Request.
	 * @package recess
	 * @static 
	 */
	public static function main(Request $request, IPolicy $policy, array $apps, RtNode $routes, array $plugins = array()) {
		static $callDepth = 0;
		static $calls = array();
		$callDepth++;
		$calls[] = $request;
		if($callDepth > 3) { 
			print_r($calls);
			die('Forwarding loop in main?');
		}
		
		$pluggedPolicy = $policy;
		
		// foreach($plugins as $plugin) {
		//	$pluggedPolicy = $plugin->decorate($pluggedPolicy);
		// }
		
		// try {
		
		$request = $pluggedPolicy->preprocess($request);
		
		// $controller = $pluggedPolicy->getControllerFor($request, $routing);
		$controller = $pluggedPolicy->getControllerFor($request, $apps, $routes);
		
		$response = $controller->serve($request);
		
		$view = $pluggedPolicy->getViewFor($response);
		
		ob_start();
		
		$view->respondWith($response);
		
		if($response instanceof ForwardingResponse) {
			$forwardRequest = new Request();
			$forwardRequest->setResource($response->forwardUri);
			$forwardRequest->method = Methods::GET;
			if(isset($response->context)) {
				$forwardRequest->get = $response->context;
			}
			
			$forwardRequest->cookies = $response->request->cookies;
			$forwardRequest->username = $response->request->username;
			$forwardRequest->password = $response->request->password;
			
			$cookies = $response->getCookies();
			if(is_array($cookies)) {
				foreach($response->getCookies() as $cookie) {	
					$forwardRequest->cookies[$cookie->name] = $cookie->value;
				}
			}
			Recess::main($forwardRequest, $policy, $apps, $routes, $plugins);
		}
		
		ob_end_flush();
		
		// $pluggedPolicy->end();
		
		// } catch(Exception $e) {
		
		//		$plugins->preHandleException($e);
		
		//		Diagnostics::handleException($e);
		
		//		$plugins->postHandleException($e);
		
		// }
		
	}
}
?>