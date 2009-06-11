<?php
Library::import('recess.lang.exceptions.InvalidAnnotationValueException');
Library::import('recess.lang.exceptions.UnknownAnnotationException');

/**
 * Base class for class, method, and property annotations.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
abstract class Annotation {
	
	protected $errors = array();
	protected $values = array();
	
	const FOR_CLASS = 1;
	const FOR_METHOD = 2;
	const FOR_PROPERTY = 4;
	
	/* Begin abstract methods */
	
	/**
	 * Returns a string representation of the intended usage of an annotation.
	 * 
	 * @return string
	 */
	abstract public function usage();
	
	/**
	 * Returns an integer representation of the type(s) of PHP language constructs
	 * the annotation is applicable to. Use the Annotation::FOR_* consts to return
	 * the desired result.
	 * 
	 * Examples:
	 *  // Only valid on classes
	 *  function isFor() { return Annotation::FOR_CLASS; }
	 *  
	 *  // Valid on methods or properties
	 *  function isFor() { return Annotation::FOR_METHOD | Annotation::FOR_PROPERTY; }
	 * 
	 * @return integer
	 */
	abstract public function isFor();
	
	/**
	 * Validate is called just before expansion. Because there may be multiple 
	 * constraints of an annotation the implementation of validate should append
	 * any error messages to the protected $errors property. Commonly used validations
	 * helper methods are provided as protected methods on the Annotation class.
	 * 
	 * @param $class The classname the annotation is on.
	 */
	abstract protected function validate($class);
	
	/**
	 * The expansion step of an annotation gives it an opportunity to manipulate
	 * a class' descriptor by introducing additional metadata, attach methods, and
	 * wrap methods.
	 * 
	 * @param string $class Classname the annotation is applied to.
	 * @param mixed $reflection The Reflection(Class|Method|Property) object the annotation is applied to.
	 * @param ClassDescriptor $descriptor The ClassDescriptor being manipulated.
	 */
	abstract protected function expand($class, $reflection, $descriptor);
	
	/* End abstract methods */
	
	/* Begin validation helper methods */
	
	protected function acceptedKeys($keys) {
		foreach($this->parameters as $key => $value) {
			if (is_string($key) && !in_array($key, $keys)) {
				$this->errors[] = "Invalid parameter: \"$key\".";
			}
		}
	}
	
	protected function requiredKeys($keys) {
		foreach($keys as $key) {
			if(!array_key_exists($key, $this->parameters)) {
				$this->errors[] = get_class($this) . " requires a '$key' parameter.";
			}
		}
	}
	
	protected function acceptedKeylessValues($values) {
		foreach($this->parameters as $key => $value) {
			if(!is_string($key) && !in_array($value, $values)) {
				$this->errors[] = "Unknown parameter: \"$value\".";
			}
		}
	}
	
	protected function acceptedIndexedValues($index, $values) {
		if(!in_array($this->parameters[$index],$values)) {
			$this->errors[] = "Parameter $index is set to \"" . $this->parameters[$key] . "\". Valid values: " . implode(', ', $values) . '.';
		}
	}
	
	protected function acceptedValuesForKey($key, $values, $case = null) {
		if(!isset($this->parameters[$key])) { return; }
		
		if($case === null) {
			$value = $this->parameters[$key];
		} else if($case === CASE_LOWER) {
			$value = strtolower($this->parameters[$key]);
		} else if($case === CASE_UPPER) {
			$value = strtoupper($this->parameters[$key]);
		}
		if(!in_array($value, $values)) {
			$this->errors[] = 'The "' . $key . '" parameter is set to "' . $this->parameters[$key] . '". Valid values: ' . implode(', ', $values) . '.';
		}
	}
	
	protected function acceptsNoKeylessValues() {
		$this->acceptedKeylessValues(array());
	}
	
	protected function acceptsNoKeyedValues() {
		$this->acceptedKeys(array());
	}
	
	protected function validOnSubclassesOf($annotatedClass, $baseClass) {
		if( !is_subclass_of($annotatedClass, $baseClass) ) {
			$this->errors[] = get_class($this) . " is only valid on objects of type $baseClass.";
		}
	}
	
	protected function minimumParameterCount($count) {
		if( ! (count($this->parameters) >= $count) ) {
			$this->errors[] = get_class($this) . " takes at least $count parameters.";
		}
	}
	
	protected function maximumParameterCount($count) {
		if( ! (count($this->parameters) <= $count) ) {
			$this->errors[] = get_class($this) . " takes at most $count parameters.";
		}
	}
	
	protected function exactParameterCount($count) {
		if ( count($this->parameters) != $count ) {
			$this->errors[] = get_class($this) . " requires exactly $count parameters.";
		}
	}
	
	/* End validation helper methods */
	
	
	function init($parameters) {
		$this->parameters = array_change_key_case($parameters, CASE_LOWER);
	}
	
	function isAValue($value) {
		return in_array($value, $this->values);
	}
	
	/**
	 * Mask other values to return the first not contained in the array.
	 * 
	 * @param $values
	 * @return value not in the array of other values
	 */
	function valueNotIn($values) {
		foreach($this->values as $value) {
			if(!in_array($value, $values)) {
				return $value;
			}
		}
		return null;
	}
	
	
	function expandAnnotation($class, $reflection, $descriptor) {		
		// First check to ensure this annotation is allowed
		// to apply to this type of PHP construct (class, method, property)
		// using a simple bitwise mask.
		if($reflection instanceof ReflectionClass) {
			$annotationIsOn = self::FOR_CLASS;
			$annotationIsOnType = 'class';
		} else if ($reflection instanceof ReflectionMethod) {
			$annotationIsOn = self::FOR_METHOD;
			$annotationIsOnType = 'method';
		} else if ($reflection instanceof ReflectionProperty) {
			$annotationIsOn = self::FOR_PROPERTY;
			$annotationIsOnType = 'property';
		}
		if(!($annotationIsOn & $this->isFor())) {
			$isFor = array();
			foreach(array('Classes' => self::FOR_CLASS, 'Methods' => self::FOR_METHOD, 'Properties' => self::FOR_PROPERTY) as $key => $mask) {
				if($mask & $this->isFor()) {
					$isFor[] = $key; 
				}
			}
			$this->errors[] = get_class($this) . ' is only valid on ' . implode(', ', $isFor) . '.';
			$typeError = true;
		} else {
			$typeError = false;
		}
		
		// Run annotation specified validations
		$this->validate($class);
		
		// Throw Exception if Annotation Errors Exist
		if(!empty($this->errors)) {
			if($reflection instanceof ReflectionProperty) {
				$message = 'Invalid ' . get_class($this) . ' on property "' . $reflection->getName() . '". ';
				$reflection = new ReflectionClass($class);
			} else {
				$message = 'Invalid ' . get_class($this) . ' on ' . $annotationIsOnType . ' "' . $reflection->getName() . '". ';
			}
			if(!$typeError) {
				$message .= "Expected usage: \n" . $this->usage();
			}
			$message .= "\n == Errors == \n * ";
			$message .= implode("\n * ", $this->errors);
			throw new RecessErrorException($message,0,0,$reflection->getFileName(),$reflection->getStartLine(),array());
		}
		
		// Map keyed parameters to properties on this annotation
		// Place unkeyed parameters on the $this->values array
		foreach($this->parameters as $key => $value) {
			if(is_string($key)) {
				$this->{$key} = $value;
			} else {
				$this->values[] = $value;
			}
		}
		
		// At this point we've processed the parameters, clearing memory
		unset($this->parameters);
		
		// Finally dispatch to abstract method expand() so that
		// Annotation developers can implement glorious new
		// functionalities.
		$this->expand($class, $reflection, $descriptor);
	}
	
	
	/**
	 * Given a docstring, returns an array of Recess Annotations.
	 * @param $docstring
	 * @return unknown_type
	 */
	static function parse($docstring) {
		preg_match_all('%(?:\s|\*)*!(\S+)[^\n\r\S]*(?:(.*?)(?:\*/)|(.*))%', $docstring, $result, PREG_PATTERN_ORDER);
		
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
			$value = preg_replace('/\s*(\(|:|,|\))[^\n\r\S]*/', '${1}', '(' . $values[$key] . ')');
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
			$fullyQualified = Library::getFullyQualifiedClassName($annotationClass);
			
			if($annotationClass != $fullyQualified || class_exists($annotationClass,false)) {
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