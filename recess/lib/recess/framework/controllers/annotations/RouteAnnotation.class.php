<?php
Library::import('recess.framework.controllers.annotations.ControllerAnnotation');
Library::import('recess.framework.routing.Route');

class RouteAnnotation extends ControllerAnnotation {
	protected $methods = array();
	protected $path;
	protected $hasError = false;
	
	function init($array) {
		// Expectation GET, /some/path/info
		if(count($array) == 1) {
			if(is_string($array[0])) {
				$fallBack = split(' ',trim($array[0]));
				if(count($fallBack) > 1) {
					$this->hasError = true;
				}
			}
			$array[1] = ' ';
		}
		
		if(count($array) != 2) {
			throw new RecessException('RouteAnnotation takes 2 parameters: METHOD[s], route.', get_defined_vars());
		}
		
		if(!is_array($array[0])) {
			$this->methods = array($array[0]);
		} else {
			$this->methods = $array[0];
		}
		
		$this->path = $array[1];
	}
	
	function massage($controller, $method, ControllerDescriptor $descriptor, ReflectionMethod $reflectedMethod = null) {
		if(isset($this->path[0]) && $this->path[0] != '/') {
			$route = new Route($controller, $method,$this->methods, $descriptor->routesPrefix . $this->path);
			$descriptor->methodUrls[$method] = $descriptor->routesPrefix . $this->path;
		} else {
			$route = new Route($controller, $method,$this->methods, $this->path);
			$descriptor->methodUrls[$method] = $this->path;
		}
		$route->fileDefined = $reflectedMethod->getFileName();
		$route->lineDefined = $reflectedMethod->getStartLine();
		$descriptor->routes[] = $route;
		
		if($this->hasError == true) {
			if(is_array($this->methods) && !empty($this->methods)) {
				$parts = split(' ', $this->methods[0]);
				if(sizeof($parts) > 1) {
					$method = $parts[0];
					$path = $parts[1];
					throw new RecessErrorException('Invalid !Route annotation. Please separate HTTP Method and Path with a comma. Ex: /** !Route ' . $method . ', ' . $path . ' */', 0, 0, $reflectedMethod->getFileName(), $reflectedMethod->getStartLine(), array());
				}
			}
			throw new RecessErrorException('Invalid !Route annotation. Please separate HTTP Method and Path with a comma. Ex: /** !Route GET, /path/info */', 0, 0, $reflectedMethod->getFileName(), $reflectedMethod->getStartLine(), array());	
		}
	}
}
?>