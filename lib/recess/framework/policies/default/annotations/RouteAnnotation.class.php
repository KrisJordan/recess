<?php
Library::import('recess.framework.policies.default.annotations.ControllerAnnotation');
Library::import('recess.framework.routing.Route');
class RouteAnnotation extends ControllerAnnotation {
	protected $methods = array();
	protected $path;
	
	function init($array) {
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
		$descriptor->routes[] = new Route($controller, $method,$this->methods, $this->path);
	}
}
?>