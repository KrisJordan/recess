<?php
/**
 * Recess! Framework reflection for class which introduces annotations.
 * Annotations follow the following syntax:
 * 
 * !AnnotationName value, key: value, value, (sub array value, key: value, (sub sub array value))
 * 
 * When parsed, AnnotationName is concatenated with 'Annotation' to derive a classname,
 * ex: !HasMany => HasManyAnnotation
 * 
 * This class is instantiated if it exists (else throws UnknownAnnotationException) and its init 
 * method is passed the value array following the annotation's name.
 * 
 * @todo Harden the regular expressions.
 * @todo Remove colon after annotation name.
 * @todo Cache annotations on a per-class basis.
 * 
 * @author Kris Jordan
 */
class RecessReflectionClass extends ReflectionClass {
	
	function getAnnotations() {
		$docstring = $this->getDocComment();
		if($docstring == '') return array();
		else {
			preg_match_all('/(?:\s|\*)*\!(\S\S*)\s\s*(.*)\s*/', $docstring, $result, PREG_PATTERN_ORDER);
			$attributes = $result[1];
			$values = $result[2];
			$returns = array();
			if(empty($result[1])) return array();
			foreach($attributes as $key => $attribute) {
				// Strip Whitespace
				$value = preg_replace('/\s*(\(|:|,|\))\s*/', '${1}', '(' . $values[$key] . ')');
				// Extract Strings
				preg_match_all('/\'(.*?)(?<!\\\\)\'|"(.*?)(?<!\\\\)"/', $value, $result, PREG_PATTERN_ORDER);
				$quoted_strings = $result[2];
				$value = preg_replace('/\'.*?(?<!\\\\)\'|".*?(?<!\\\\)"/', '%s', $value);
				// Insert Single Quotes
				$value = preg_replace('/((?!\(|,|:))(?!\))(.*?)((?=\)|,|:))/', '${1}\'${2}\'${3}', $value);
				// Array Keyword
				$value = str_replace('(','array(',$value);
				// Arrows
				$value = str_replace(':', '=>', $value);
				
				$value = vsprintf($value . ';', $quoted_strings);
				
				@eval('$array = ' . $value);
				if(!isset($array)) { 
					Library::import('recess.lang.exceptions.InvalidAnnotationValueException');
					throw new InvalidAnnotationValueException('In class "' . $this->name . '", there is an unparseable attribute value: "!' . $attribute . ': ' . $values[$key] . '"',0,0,$this->getFileName(),$this->getStartLine(),get_defined_vars());
				}
				
				$annotationClass = $attribute . 'Annotation';
				
				if(in_array($annotationClass,get_declared_classes())) { // TODO: Less expensive way of handling this than calling get_declared_classes?
					$annotation = new $annotationClass;
					$annotation->init($array);
				} else {
					Library::import('recess.lang.exceptions.UnknownAnnotationException');
					throw new UnknownAnnotationException('In class "' . $this->name . '", there is an unknown annotation: "' . $attribute . '"',0,0,$this->getFileName(),$this->getStartLine(),get_defined_vars());
				}
				
				$returns[] = $annotation;
			}
			unset($attributes,$values,$result);
			return $returns;
		}
	}
	
	function getMethods() {
		
	}
	
}

?>