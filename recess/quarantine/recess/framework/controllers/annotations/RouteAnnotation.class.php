<?php
Library::import('recess.lang.Annotation');
Library::import('recess.framework.routing.Route');

class RouteAnnotation extends Annotation {
	
	const EMPTY_PATH = ' ';
	
	protected $httpMethods = array();
	protected $path = self::EMPTY_PATH;
	
	public function usage() {
		return '!Route ( GET | POST | PUT | DELETE)[, route/path/here]';
	}

	public function isFor() {
		return Annotation::FOR_METHOD;
	}

	protected function validate($class) {
		$this->minimumParameterCount(1);
		$this->maximumParameterCount(2);
		$this->validOnSubclassesOf($class, Controller::CLASSNAME);
		$this->acceptedIndexedValues(0, array(Methods::GET, Methods::POST, Methods::PUT, Methods::DELETE));
	}
	
	protected function expand($class, $reflection, $descriptor) {
		if(is_array($this->values[0])) {
			$this->httpMethods = $this->values[0];
		} else {
			$this->httpMethods = array($this->values[0]);
		}
		
		if(isset($this->values[1])) {
			$this->path = $this->values[1];
		}
		
		$controller = Library::getFullyQualifiedClassName($class);
		$controllerMethod	= $reflection->getName();
		
		if(strpos($this->path, Library::pathSeparator)===0) {
			// Absolute Route
			$route = new Route($controller, $controllerMethod, $this->httpMethods, $this->path);
			$descriptor->methodUrls[$controllerMethod] = $this->path;
		} else {
			// Relative Route
			$route = new Route($controller, $controllerMethod, $this->httpMethods, $descriptor->routesPrefix . $this->path);
			$descriptor->methodUrls[$controllerMethod] = $descriptor->routesPrefix . $this->path;
		}
		
		$route->fileDefined = $reflection->getFileName();
		$route->lineDefined = $reflection->getStartLine();
		
		$descriptor->routes[] = $route;
	}
}
?>