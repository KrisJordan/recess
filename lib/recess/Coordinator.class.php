<?php

Library::import('application.Application');
Library::import('recess.http.Environment');

class Coordinator {
	/**
	 * Responsible for coordinating the entire lifecycle of an HTTP request.
	 * This static method handles the flow from input request to output response.
	 * 
	 * @static
	 */
	public static function coordinate() {
		
		$policy = Application::getPolicy();
		
		$preprocessor = $policy->getPreprocessor();
		
		$request = $preprocessor->process(Environment::getRawRequest());
		
		$controller = $policy->getControllerFor($request);
		
		$response = $controller->serve($request);
		
		$view = $policy->getViewFor($response);
		
		$view->respondWith($response);
		
	}
}

?>