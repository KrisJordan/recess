<?php
Library::import('recess.http.Methods');
Library::import('recess.http.Request');
Library::import('recess.http.Formats');
Library::import('recess.http.responses.BadRequestResponse');
Library::import('recess.lang.Inflector');

/**
 * The controller is responsible for interpretting a preprocessed Request,
 * performing some action in response to the Request (usually CRUDS), and
 * returning a Response which contains relevant state for a view to render
 * the Response.
 * 
 * @author Kris Jordan
 */
abstract class AbstractController {
	/**
	 * The routes which map to a controller's methods
	 * @todo Refactor this out and use reflection in getRoutes() with Recess! Annotations
	 */
	protected $routes = array();
	
	/** The formats/content-types which a controller responds to. */
	protected $formats = array(Formats::XHTML);
	
	public function __construct() {	}
	
	/**
	 * Routes map URIs to a Controller's method.
	 * @todo Refactor this to use Recess! Annotations. In fact, factor this out of controller.
	 * @return array(Routes)
	 */
	public function getRoutes() {
		Library::import('recess.lang.RecessReflectionClass');
		$reflectionClass = new RecessReflectionClass($this);
		$methods = $reflectionClass->getMethods();
		foreach($methods as $method) {
			$annotations = $method->getAnnotations();
			foreach($annotations as $annotation) {
				if($annotation instanceof RouteAnnotation) {
					$this->routes[] = new Route($this,$method->name,$annotation->methods,$annotation->path);
				}
			}
		}
		return $this->routes;
	}
	
	/**
	 * The entry point from the Recess into a Controller. The Controller is responsible
	 * for the Inversion-of-Control dispatch to one of its own methods.
	 * @todo Should the meat of this logic be refactored out for a clean base class?
	 * @param Request $request The preprocessed recess.http.Request
	 * @return Response
	 */
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
						// Map function_args to expected parameters
						$callArguments = array($request);
						foreach($functionParameters as $parameter) {
							if($parameter->getPosition() == 0) continue;
							if(!isset($request->meta['function_args'][$parameter->getName()])) {
								if(!$parameter->isOptional()) {
									throw new RecessException('Controller method "' . $functionName . '" expects ' . $parameterCount . ' arguments, given ' . count($request->meta['function_args']) . ' and missing required parameter: ' . $parameter->name);
								}
							} else {
								$callArguments[] = $request->meta['function_args'][$parameter->getName()];
							}							
						}
						$response = $function->invokeArgs($this, $callArguments);
					} else {
						throw new RecessException('Controller method expects routed functions.', get_defined_vars());
					}
				}
				
				if($response instanceof Response) {
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