<?php
Library::import('application.Application');
Library::import('recess.http.Environment');
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
	public static function main(Request $request) {
		
		$policy = Application::getPolicy($request);
		
		$preprocessor = $policy->getPreprocessor();
		
		$request = $preprocessor->process($request);
		
		$controller = $policy->getControllerFor($request);
		
		$response = $controller->serve($request);
		
		$view = $policy->getViewFor($response);
		
		$view->respondWith($response);
		
	}
}

?>