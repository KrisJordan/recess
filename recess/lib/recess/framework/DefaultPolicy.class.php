<?php
Library::import('recess.framework.controllers.Controller');
Library::import('recess.framework.views.View');
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

		if($request->format != Formats::xhtml) {
			$this->reparameterizeForFormat($request);
		}
		
		return $request;
	}
	
	public function getControllerFor(Request &$request, array $applications, RoutingNode $routes) {
		
		$routeResult = $routes->findRouteFor($request);
		
		if($routeResult->routeExists) {
			if($routeResult->methodIsSupported) {
				$controller = $this->getControllerFromRouteResult($request, $routeResult);
			} else {
				// TODO: Shortwire a result here for a method not supported HTTP response.
				throw new RecessError('METHOD not supported.');
			}
		} else {
			$controller = $this->getControllerFromResourceString($request, $applications);
		}
		
		$this->controller = $controller;
		
		return $controller;
	}
	
	public function getViewFor(Response &$response) {
		$view = Library::importAndInstantiate($response->meta->viewClass);
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
	
	protected function getControllerFromRouteResult(Request &$request, RoutingResult $routeResult) {
		$request->meta->app = $routeResult->route->app;
		$request->meta->controllerMethod = $routeResult->route->function;
		$request->meta->controllerMethodArguments = $routeResult->arguments;
		$request->meta->useAssociativeArguments = true;
		$controllerClass = $routeResult->route->class;
		Library::beginNamedRun($controllerClass);
		Library::import($controllerClass);
		$controllerClass = Library::getClassName($controllerClass);
		$controller = new $controllerClass($routeResult->route->app);
		return $controller;
	}
	
	protected function getControllerFromResourceString(Request &$request, array $applications) {
		$partsCount = count($request->resourceParts);
		
		$prefixes = array_map(create_function('$app','return $app->routingPrefix;'), $applications);
		
		$request->meta->controllerMethodArguments = array();
		$request->meta->useAssociativeArguments = false;
		// TODO: Clean this up. Throw better errors to reveal internal logic.
		switch($partsCount) {
			case 0: // DefaultApp, HomeController, home method
				$defaultAppKey = array_search('',$prefixes);
				if($defaultAppKey !== false) {
					$request->meta->controllerMethod = 'home';
					$request->meta->app = $applications[$defaultAppKey];
						Library::beginNamedRun('HomeController');
					return $applications[$defaultAppKey]->getController('HomeController');
				}
				break;
			case 1: // 
				$appKey = array_search($request->resourceParts[0], $prefixes);
				if($appKey !== false) {
					$request->meta->controllerMethod = 'home';
					$request->meta->app = $applications[$appKey];
						Library::beginNamedRun($request->resourceParts[0] . 'Controller');
					return $applications[$appKey]->getController('HomeController');
				} else {
					$defaultAppKey = array_search('',$prefixes);
					if($defaultAppKey !== false) {
						$request->meta->controllerMethod = 'home';
						$request->meta->app = $applications[$defaultAppKey];
						Library::beginNamedRun($request->resourceParts[0] . 'Controller');
						return $applications[$defaultAppKey]->getController($request->resourceParts[0] . 'Controller');
					}
				}
				break;
			case 2:
				$appKey = array_search($request->resourceParts[0], $prefixes);
				if($appKey !== false) {
					$request->meta->controllerMethod = 'home';
					$request->meta->app = $applications[$appKey];
						Library::beginNamedRun($request->resourceParts[1] . 'Controller');
					return $applications[$appKey]->getController($request->resourceParts[1] . 'Controller');
				} else {
					$defaultAppKey = array_search('',$prefixes);
					if($defaultAppKey !== false) {
						$request->meta->controllerMethod = $request->resourceParts[1];
						$request->meta->app = $applications[$defaultAppKey];
						Library::beginNamedRun($request->resourceParts[0] . 'Controller');
						return $applications[$defaultAppKey]->getController($request->resourceParts[0] . 'Controller');
					}
				}
				break;
			default:
				$appKey = array_search($request->resourceParts[0], $prefixes);
				if($appKey !== false) {
					$request->meta->controllerMethodArguments = array_slice($request->resourceParts,3);
					$request->meta->controllerMethod = $request->resourceParts[2];
					$request->meta->app = $applications[$appKey];
					Library::beginNamedRun($request->resourceParts[1] . 'Controller');
					return $applications[$appKey]->getController($request->resourceParts[1] . 'Controller');
				} else {
					$defaultAppKey = array_search('',$prefixes);
					if($defaultAppKey !== false) {
						$request->meta->controllerMethodArguments = array_slice($request->resourceParts,2);
						$request->meta->controllerMethod = $request->resourceParts[1];
						$request->meta->app = $applications[$defaultAppKey];
						Library::beginNamedRun($request->resourceParts[0] . 'Controller');
						return $applications[$defaultAppKey]->getController($request->resourceParts[0] . 'Controller');
					}
				}
				break;
		}
	}
}

?>