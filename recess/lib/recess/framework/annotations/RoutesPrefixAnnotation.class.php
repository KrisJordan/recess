<?php
Library::import('recess.framework.annotations.ControllerAnnotation');
Library::import('recess.framework.routing.Route');

class RoutesPrefixAnnotation extends ControllerAnnotation {
	protected $prefix;
	
	function init($array) {
		if(count($array) == 1) {
			$this->prefix = $array[0];
		} else {
			throw new RecessException('RoutePrefixAnnotation takes 1 parameter: prefix.', get_defined_vars());
		}
	}
	
	function massage($controller, $method, ControllerDescriptor $descriptor) {
		$descriptor->routesPrefix = $this->prefix;
	}
}
?>