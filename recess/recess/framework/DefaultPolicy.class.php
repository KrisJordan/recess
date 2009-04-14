<?php
Library::import('recess.framework.controllers.Controller');
Library::import('recess.framework.views.RecessView');
Library::import('recess.framework.views.NativeView');
Library::import('recess.framework.interfaces.IPolicy');

class DefaultPolicy implements IPolicy {
	protected $controller;
		
	/**
	 * Used to pre-process a request.
	 * This may involve extracting information and transforming values. 
	 * For example, Transforming the HTTP method from POST to PUT based on a POSTed field.
	 * 
	 * @param	Request The Request to refine.
	 * @return	Request The refined Request.
	 */
	public function preprocess(Request &$request) {
		
		$this->getHttpMethodFromPost($request);

		$this->getFormatFromResourceString($request);

		if($request->format != Formats::XHTML) {
			$this->reparameterizeForFormat($request);
		}
		
//		if($request->method == Methods::OPTIONS) {
//			$response = new 
//		}
		
		return $request;
	}
	
	public function getControllerFor(Request &$request, array $applications, RtNode $routes) {
		
		$routeResult = $routes->findRouteFor($request);
		
		if($routeResult->routeExists) {
			if($routeResult->methodIsSupported) {
				$controller = $this->getControllerFromRouteResult($request, $routeResult);
			} else {
				throw new RecessResponseException('METHOD not supported, supported METHODs are: ' . implode(',', $routeResult->acceptableMethods), ResponseCodes::HTTP_METHOD_NOT_ALLOWED, get_defined_vars());
			}
		} else {
			throw new RecessResponseException('Resource does not exist.', ResponseCodes::HTTP_NOT_FOUND, get_defined_vars());
		}
		
		$this->controller = $controller;
		
		return $controller;
	}
	
	public function getViewFor(Response &$response) {
		$view = new $response->meta->viewClass;
		$response->meta->viewDir = $response->meta->app->getViewsDir() . $response->meta->viewPrefix;
		return $view;
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
				$request->format = Formats::XHTML;
			}
		}
		return $request;
	}

	protected function reparameterizeForFormat(Request &$request) {
		if($request->format == Formats::JSON) {
			$method = strtolower($request->method);
			$request->$method = json_decode($request->input, true);
		} else if ($request->format == Formats::XML) {
			// TODO: XML reparameterization in request transformer
		}
		return $request;
	}
	
	protected function getControllerFromRouteResult(Request &$request, RoutingResult $routeResult) {
		$request->meta->app = $routeResult->route->app;
		$request->meta->controllerMethod = $routeResult->route->function;
		$request->meta->controllerMethodArguments = $routeResult->arguments;
		$request->meta->useAssociativeArguments = true;
		$controllerClass = $routeResult->route->class;
		Library::import($controllerClass);
		$controllerClass = Library::getClassName($controllerClass);
		$controller = new $controllerClass($routeResult->route->app);
		$request->meta->controller = $controller;
		return $controller;
	}

}

?>