<?php
Library::import('recess.lang.exceptions.InvalidAnnotationValueException');
Library::import('recess.lang.exceptions.UnknownAnnotationException');

/**
 * Base class for class and method annotations.
 * @author Kris Jordan
 */
abstract class Annotation {
	/**
	 * Initialize the Annotation with value array.
	 * @param array The list of parameters.
	 */
	abstract function init($array);
	
	static function parse($docstring) {
		//preg_match_all('/(?:\s|\*)*\!(\S\S*)\s\s*(.*)\s*/', $docstring, $result, PREG_PATTERN_ORDER);
		preg_match_all('%(?:\s|\*)*!(\S\S*)\s\s*(?:(.*)(?>\*/)|(.*))%', $docstring, $result, PREG_PATTERN_ORDER);
		//print_r($result);
		$annotations = $result[1];
		if(isset($result[2][0]) && $result[2][0] != '') {
			$values = $result[2];
		} else { 
			$values = $result[3];
		}
		$returns = array();
		if(empty($result[1])) return array();
		foreach($annotations as $key => $annotation) {
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
				throw new InvalidAnnotationValueException('There is an unparseable annotation value: "!' . $annotation . ': ' . $values[$key] . '"',0,0,'',0,array());
			}
			
			$annotationClass = $annotation . 'Annotation';
			
			if(in_array($annotationClass,get_declared_classes())) { // TODO: Less expensive way of handling this than calling get_declared_classes?
				$annotation = new $annotationClass;
				$annotation->init($array);
			} else {
				throw new UnknownAnnotationException('Unknown annotation: "' . $annotation . '"',0,0,'',0,get_defined_vars());
			}
			
			$returns[] = $annotation;
		}
		unset($annotations,$values,$result);
		return $returns;
	}
}
?>