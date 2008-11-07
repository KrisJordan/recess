<?php
Library::import('recess.lang.Annotation');

class RecessReflectionMethod extends ReflectionMethod {
	function getAnnotations() {
		$docstring = $this->getDocComment();
		if($docstring == '') return array();
		else {
			$returns = array();
			try {
				$returns = Annotation::parse($docstring);
			} catch(InvalidAnnotationValueException $e) {			
				throw new InvalidAnnotationValueException('In class "' . $this->getDeclaringClass()->name . '" on method "'. $this->name .'".' . $e->getMessage(),0,0,$this->getFileName(),$this->getStartLine(),array());
			} catch(UnknownAnnotationException $e) {
				throw new UnknownAnnotationException('In class "' . $this->getDeclaringClass()->name . '" on method "'. $this->name .'".' . $e->getMessage(),0,0,$this->getFileName(),$this->getStartLine(),array());
			}
		}
		return $returns;
	}
}
?>