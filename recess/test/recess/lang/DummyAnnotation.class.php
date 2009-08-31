<?php
namespace made\up\space;

use recess\lang\Annotation;
class DummyAnnotation extends Annotation {
	/* Begin abstract methods */
	
	/**
	 * Returns a string representation of the intended usage of an annotation.
	 * 
	 * @return string
	 */
	public function usage() {
		return "";
	}
	
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
	public function isFor() {
		return Annotation::FOR_CLASS | Annotation::FOR_METHOD | Annotation::FOR_PROPERTY;
	}
	
	/**
	 * Validate is called just before expansion. Because there may be multiple 
	 * constraints of an annotation the implementation of validate should append
	 * any error messages to the protected $errors property. Commonly used validations
	 * helper methods are provided as protected methods on the Annotation class.
	 * 
	 * @param $class The classname the annotation is on.
	 */
	protected function validate($class) {
		return true;
	}
	
	/**
	 * The expansion step of an annotation gives it an opportunity to manipulate
	 * a class' descriptor by introducing additional metadata, attach methods, and
	 * wrap methods.
	 * 
	 * @param string $class Classname the annotation is applied to.
	 * @param mixed $reflection The Reflection(Class|Method|Property) object the annotation is applied to.
	 * @param ClassDescriptor $descriptor The ClassDescriptor being manipulated.
	 */
	protected function expand($class, $reflection, $descriptor) {
		return $descriptor;
	}
}