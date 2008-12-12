<?php
Library::import('recess.lang.Annotation');

abstract class ControllerAnnotation extends Annotation {
	
	abstract function massage($controller, $method, ControllerDescriptor $descriptor, ReflectionMethod $reflectedMethod = null);
}

?>