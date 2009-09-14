<?php
namespace made\up\space;

use recess\lang\Annotation;
class ValidateAnnotation extends Annotation {

	public function usage() {
		return "";
	}
	
	public function isFor() {
		return Annotation::FOR_METHOD;
	}
	
	public function validate($class) {
		// This is hacky but makes testing a little easier
		if($class=='Basic') {
			$this->acceptedKeys(array('KeyA','KeyB','KeyD'));
			$this->requiredKeys(array('KeyA'));
			$this->acceptedKeylessValues(array('AValue','BValue'));
			$this->acceptedIndexedValues(0, array('AValue'));
			$this->acceptedValuesForKey('KeyA',array('foo'),CASE_LOWER);
			$this->acceptedValuesForKey('KeyB',array('bar'));
			$this->acceptedValuesForKey('KeyD',array('FOOBAR'),CASE_UPPER);
		} else if($class == 'AcceptsNoKeylessValues') {
			$this->acceptsNoKeylessValues();
		} else if($class == 'AcceptsNoKeyedValues') {
			$this->acceptsNoKeyedValues();
		} else if($class == 'made\up\space\ValidateAnnotation' || $class == 'recess\lang\Object') {
			$this->validOnSubclassesOf($class,'recess\lang\Annotation');
		} else if($class == 'MinMax') {
			$this->minimumParameterCount(1);
			$this->maximumParameterCount(2);
		} else if($class == 'Exact') {
			$this->exactParameterCount(1);
		} else if($class == 'AnnotationTest') {
			$this->acceptsNoKeylessValues();
		}
	}
	
	protected function expand($class, $reflection, $descriptor) {
		return $descriptor;
	}
}