<?php
Library::import('recess.lang.Annotation');

class PrefixAnnotation extends Annotation {
	
	public function usage() {
		return '!Prefix prefix/of/route/ [, Views: prefix/, Routes: prefix/]';
	}

	public function isFor() {
		return Annotation::FOR_CLASS;
	}

	protected function validate($class) {
		$this->acceptedKeys(array('views', 'routes'));
		$this->minimumParameterCount(1);
		$this->maximumParameterCount(3);
		$this->validOnSubclassesOf($class, Controller::CLASSNAME);
	}
	
	protected function expand($class, $reflection, $descriptor) {
		if(isset($this->values[0])) {
			$viewsPrefix = $routesPrefix = $this->values[0];
		} else {
			$viewsPrefix = $routesPrefix = '';
		}

		if(isset($this->views)) { $viewsPrefix = $this->views; }
		if($viewsPrefix == '/') { $viewsPrefix = ''; }
		$descriptor->viewsPrefix = $viewsPrefix;
		
		if(isset($this->routes)) { $routesPrefix = $this->routes; }
		if($routesPrefix == '/') { $routesPrefix = ''; }
		$descriptor->routesPrefix = $routesPrefix;
	}
}
?>