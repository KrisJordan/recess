<?php
Library::import('recess.framework.annotations.ControllerAnnotation');
Library::import('recess.framework.routing.Route');

class RouteAnnotation extends ControllerAnnotation {
	protected $methods = array();
	protected $path;
	
	function init($array) {
		if(count($array) == 1) {
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
	
	function massage($controller, $method, ControllerDescriptor $descriptor) {
		if(isset($this->path[0]) && $this->path[0] != '/') {
			$descriptor->routes[] = new Route($controller, $method,$this->methods, $descriptor->routesPrefix . $this->path);
			$descriptor->methodUrls[$method] = $descriptor->routesPrefix . $this->path;
		} else {
			$descriptor->routes[] = new Route($controller, $method,$this->methods, $this->path);
			$descriptor->methodUrls[$method] = $this->path;
		}
	}
}
?>