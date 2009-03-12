<?php
Library::import('recess.lang.Annotation');

class ViewAnnotation extends Annotation {

	const PREFIX = 'prefix';
	
	protected $prefix = '';
	
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
		$this->validOnInstancesOf($class, Controller::CLASSNAME);
	}
	
	protected function expand($class, $reflection, $descriptor) {
		$descriptor->viewClass = 'recess.framework.views.' . $this->values[0] . 'View';
		$descriptor->viewPrefix = $this->prefix;
	}

}
?>