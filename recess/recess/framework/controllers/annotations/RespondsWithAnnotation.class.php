<?php
Library::import('recess.lang.Annotation');

class RespondsWithAnnotation extends Annotation {
	
	public function usage() {
		return	"!RespondsWith View[, View, ...]\n";
	}

	public function isFor() {
		return Annotation::FOR_CLASS;
	}

	protected function validate($class) {
		$this->minimumParameterCount(1);
		$this->validOnSubclassesOf($class, Controller::CLASSNAME);
	}
	
	protected function expand($class, $reflection, $descriptor) {
		if(!isset($descriptor->respondWith) || !is_array($descriptor->respondWith)) {
			$descriptor->respondWith = array();
		}
		
		foreach($this->values as $value) {
			$viewClass = $value . 'View';
			if(!in_array($viewClass, $descriptor->respondWith)) {
				$descriptor->respondWith[] = $viewClass;
			}
		}
		
//		if($reflection instanceof ReflectionClass) {
//			$this->expandClass($class, $reflection, $descriptor);
//		} else if ($reflection instanceof ReflectionMethod) { 
//			$this->expandMethod($class, $reflection, $descriptor);
//		}
	}
	
//	protected function expandClass($class, $reflectionClass, $descriptor) {
//		$descriptor->respondWith = $this->values;
//	}
//	
//	protected function expandMethod($class, $reflectionMethod, $descriptor) {
//		
//	}
}
?>