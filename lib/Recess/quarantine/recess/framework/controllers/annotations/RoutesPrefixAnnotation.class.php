<?php
Library::import('recess.lang.Annotation');

class RoutesPrefixAnnotation extends Annotation {
	
	public function usage() {
		return '!RoutesPrefix prefix/of/route/';
	}

	public function isFor() {
		return Annotation::FOR_CLASS;
	}

	protected function validate($class) {
		$this->acceptsNoKeyedValues();
		$this->exactParameterCount(1);
		$this->validOnSubclassesOf($class, Controller::CLASSNAME);
	}
	
	protected function expand($class, $reflection, $descriptor) {
		$prefix = $this->values[0];
		if($prefix == '/')
			$descriptor->routesPrefix = '';
		else
			$descriptor->routesPrefix = $prefix;
	}

}
?>