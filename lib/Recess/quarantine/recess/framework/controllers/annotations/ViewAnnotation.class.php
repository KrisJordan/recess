<?php
Library::import('recess.lang.Annotation');

class ViewAnnotation extends Annotation {

	const PREFIX = 'prefix';
	
	protected $prefix = '';
	
	protected $viewClass = 'LayoutsView';
	
	public function usage() {
		return '!View ViewProvider [, Prefix: pathWithinViews/]';
	}

	public function isFor() {
		return Annotation::FOR_CLASS;
	}

	protected function validate($class) {
		$this->acceptedKeys(array(self::PREFIX));
		$this->minimumParameterCount(1);
		$this->maximumParameterCount(2);
		$this->validOnSubclassesOf($class, Controller::CLASSNAME);
	}
	
	protected function expand($class, $reflection, $descriptor) {
		if(isset($this->values[0])) {
			$this->viewClass = $this->values[0];
			if(strpos($this->viewClass, 'View') === false) {
				$this->viewClass .= 'View';
			}
		}
		$descriptor->viewClass = $this->viewClass;
		$descriptor->viewsPrefix = $this->prefix;
	}

}
?>