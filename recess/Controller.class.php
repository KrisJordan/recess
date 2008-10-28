<?php

Library::import('recess.http.Methods');
Library::import('recess.http.Request');
Library::import('recess.http.Formats');
Library::import('recess.http.responses.BadRequestResponse');
Library::import('recess.interfaces.IController');

abstract class Controller implements IController {
	protected $routes = array();
	protected $formats = array(Formats::xhtml);
	
	public function __construct() {
		
	}
	
	public function getRoutes() {
		return $this->routes;
	}
	
	public function serve(Request $request) {
		// TODO: Beautify this code, break it up into steps.
		$response = null;
		
		if(!in_array($request->format, $this->formats)) {
			return new BadRequestResponse($request);
		}
		
		// First preference: use meta information gleaned in routing in preprocessor
		$class = new ReflectionClass(get_class($this));
		if(isset($request->meta['function'])) {
			$functionName = $request->meta['function'];
		} else {
			// Fallback: use second part of resource to see if there is a matching method
			//  using the format (http_method)(SecondResourcePart)
			//  ie: GET /cars/all -> getAll
			//      PUT /cars/byId/1 -> putById
			if(isset($request->resourceParts[1])) {
				$functionName = strtolower($request->method) . Inflector::toProperCaps($request->resourceParts[1]);
			}
		}
		
		if(isset($functionName)) {
			if($class->hasMethod($functionName)) {
				$function = $class->getMethod($functionName);
				$functionParameters = $function->getParameters();
				$parameterCount = count($functionParameters);
				
				if($parameterCount < 1) {
					// TODO: Decide whether this should be the expected behavior.
					//  Seems we ALWAYS want to pass the Request object.
					$response = $function->invoke($this);
				} else if($parameterCount == 1) {
					$response = $function->invoke($this, $request);
				} else {
					// Parameters are requested inbound
					if(isset($request->meta['function_args']) && is_array($request->meta['function_args'])) {
						if(count($request->meta['function_args']) == $parameterCount - 1) {
							// Map function_args to expected parameters
							$callArguments = array($request);
							foreach($functionParameters as $parameter) {
								if($parameter->getPosition() == 0) continue;
								$callArguments[] = $request->meta['function_args'][$parameter->getName()];
							}
							$response = $function->invokeArgs($this, $callArguments);
						} else {
							throw new RecessException('Controller method "' . $functionName . '" expects ' . $parameterCount . ' arguments, given ' . count($request->meta['function_args']));
						}
					} else {
						throw new RecessException('Controller method expects routed functions.', get_defined_vars());
					}
				}
				
				if(is_a($response, 'Response')) {
					return $response;
				} else {
					return new BadRequestResponse($request);
				}
			}
		}
		
		return new BadRequestResponse($request);
	}
}

?>