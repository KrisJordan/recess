<?php

Library::import('recess.framework.policies.default.Controller');
Library::import('recess.framework.policies.default.View');
Library::import('recess.framework.interfaces.IPolicy');

class DefaultPolicy implements IPolicy {
	
	/**
	 * Used to pre-process a request.
	 * This may involve extracting information and transforming values. 
	 * For example, Transforming the HTTP method from POST to PUT based on a POSTed field.
	 * 
	 * @param	Request The Request to refine.
	 * @return	Request The refined Request.
	 */
	public function preprocess(Request $request) {
		
		$this->getHttpMethodFromPost($request);

		$this->getFormatFromResourceString($request);

		if($request->format != Formats::xhtml) {
			$this->reparameterizeForFormat($request);
		}
		
		$this->selectControllerAndView($request);

		return $request;
	}
	
	public function getControllerFor(Request $request) {
		return Library::importAndInstantiate($request->meta->controllerClass);
	}
	
	public function getViewFor(Response $response) {
		if(Library::classExists('recess.framework.policies.default.' . $response->meta->viewClass)) {
			return new $response->meta->viewClass;
		} else {
			Application::loadView($response->meta->viewClass);
			return new $response->meta->viewClass;
		}
	}
	
	/////////////////////////////////////////////////////////////////////////
	// Helper Methods

	const HTTP_METHOD_FIELD = '_METHOD';

	protected function getHttpMethodFromPost(Request &$request) {
		if(array_key_exists(self::HTTP_METHOD_FIELD, $request->post)) {
			$request->method = $request->post[self::HTTP_METHOD_FIELD];
			unset($request->post[self::HTTP_METHOD_FIELD]);
			if($request->method == Methods::PUT) {
				$request->put = $request->post;
			}
		}
		return $request;
	}

	protected function getFormatFromResourceString(Request &$request) {
		$lastPartIndex = count($request->resourceParts) - 1;
		if($lastPartIndex < 0) return $request;
		
		$lastPart = $request->resourceParts[$lastPartIndex];
		
		$lastDotPosition = strrpos($lastPart, Library::dotSeparator);
		if($lastDotPosition !== false) {
			$substring = substr($lastPart, $lastDotPosition + 1);
			if(in_array($substring, Formats::$all)) {
				$request->format = $substring;
				$request->setResource(substr($request->resource, 0, strrpos($request->resource, Library::dotSeparator)));
			} else {
				$request->format = Formats::xhtml;
			}
		}
		return $request;
	}

	protected function reparameterizeForFormat(Request &$request) {
		// TODO: Think about how parameter passing via json/xml/post-vars can be streamlined
		if($request->format == Formats::json) {
			if(array_key_exists('json', $request->post)){
				$request->post = json_decode($request->post['json']);
			}
		} else if ($request->format == Formats::xml) {
			// TODO: XML reparameterization in request transformer
		}
		return $request;
	}
	
	protected function selectControllerAndView(Request &$request) {
		$router = Config::getRouter(); // TODO: change this
		
		$routerResult = $router->findRouteFor($request);
		if($routerResult->routeExists) {
			$request = $this->selectControllerAndViewUsingRoutingResult($request, $routerResult);
		} else {
			$request = $this->selectControllerAndViewUsingDefaults($request);
		}
		
		return $request;
	}
	
	protected function selectControllerAndViewUsingRoutingResult(Request &$request, RoutingResult &$routerResult) {
		$request->meta->useAssociativeArguments = true;
		if($routerResult->methodIsSupported) {
			$request->meta->controllerClass = $routerResult->route->class;
			$request->meta->controllerMethod = $routerResult->route->function;
			$request->meta->controllerMethodArguments = $routerResult->arguments;		
		} else {
			// TODO: Exception Case - Need a means of shortcutting a "METHOD NOT SUPPORTED" 
			throw new RecessException($request->method . ' HTTP method is not supported for url: ' . $request->resource);
		}
		return $request;
	}
	
	protected function selectControllerAndViewUsingDefaults(Request &$request) {
		$request->meta->useAssociativeArguments = false;
		
		// here we need to check against app prefixes
		// see if first in list of prefixes
		//		yes, use prefix application
		
		if(isset($request->resourceParts[0])) {
			$request->meta->controllerClass = $request->resourceParts[0] . 'Controller';
			if(isset($request->resourceParts[1])) {
				$request->meta->controllerMethod = $request->resourceParts[1];
			} else {
				$request->meta->controllerMethod = 'home';
			}
			if(count($request->resourceParts) > 2) {
				$request->meta->controllerMethodArguments = array_slice($request->resourceParts, 2);
			} else {
				$request->meta->controllerMethodArguments = array();
			}
		} else {
			$request->meta->controllerClass = 'HomeController';
			$request->meta->controllerMethod = 'home';
			$request->meta->controllerMethodArguments = array();
		}
		return $request;
	}
}

?>