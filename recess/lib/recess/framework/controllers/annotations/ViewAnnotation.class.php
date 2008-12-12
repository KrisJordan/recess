<?php
Library::import('recess.framework.controllers.annotations.ControllerAnnotation');

class ViewAnnotation extends ControllerAnnotation {

	protected $viewClass = 'Native';
	protected $prefix = '';
	
	function init($values) {
		if(isset($values[0])) {
			$this->viewClass = $values[0];
		} else {
			throw new RecessException('View annotation requires a view class (like Smarty or Native).');
		}
		
		if(isset($values['Prefix'])) {
			$this->prefix = $values['Prefix'];
		}
	}
	
	function massage($controller, $method, ControllerDescriptor $descriptor, ReflectionMethod $reflectedMethod = null) {
		$descriptor->viewClass = 'recess.framework.views.' . $this->viewClass . 'View';
		$descriptor->viewPrefix = $this->prefix;
	}
	
}
?>