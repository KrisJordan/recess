<?php

Library::import('recess.http.ForwardingResponse');
/**
 * Entry into Recess! Framework occurs in the coordinator. It is responsible
 * for the flow of control from preprocessing of request data, the serving of a request
 * in a controller, and rendering a response to the request through a view.
 * 
 * @author Kris Jordan
 * @final 
 */
final class Coordinator {
	/**
	 * Recess! Framework Entry Point
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
		
		$view->respondWith($response);
		
		if($response instanceof ForwardingResponse) {
			$forwardRequest = new Request();
			$forwardRequest->setResource($response->forwardUri);
			$forwardRequest->method = Methods::GET;
			if(isset($response->context)) {
				$forwardRequest->get = $response->context;
			}
			Coordinator::main($forwardRequest, $policy, $apps, $routes, $plugins);
		}
		
		// $pluggedPolicy->end();
		
		// } catch(Exception $e) {
		
		//		$plugins->preHandleException($e);
		
		//		Diagnostics::handleException($e);
		
		//		$plugins->postHandleException($e);
		
		// }
		
	}
}
?>