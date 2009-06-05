<?php
Library::import('recess.lang.Annotation');
Library::import('recess.lang.MethodCallWrapper');

/**
 * The After annotation can be used as a shortcut for registering
 * methods that should be called after a wrapped method. It is placed
 * on the method to be called and referencess the wrappable method.
 * 
 * For example, on a subclass of Model: 
 * /** !After create, insert *_/
 * function log($success) { echo $success ? "Success!" : "Fail!"; }
 * 
 * There are a couple of nuances worth noting:
 * 
 * -Your after method must take one argument that is the value returned
 *  the wrapped method.
 * -Your method should return a value of the same type as expected
 *  to be returned by the wrapped method. Returning null, or not returning,
 *  has the same effect as returning the value returned by the wrapped
 *  method.
 *  
 *  @author Kris Jordan
 *  @since 0.20
 */
class AfterAnnotation extends Annotation {
	
	/**
	 * Returns a string representation of the intended usage of an annotation.
	 * 
	 * @return string
	 */
	public function usage() {
		return '!After wrappableMethod[, wrappableMethod]*';
	}
	
	/**
	 * After is only used on methods.
	 * 
	 * @return integer
	 */
	public function isFor() {
		return Annotation::FOR_METHOD;
	}
	
	/**
	 * Validates some constraints of the annotation's specification.
	 * 
	 * @param $class The classname the annotation is on.
	 */
	protected function validate($class) {
		$this->minimumParameterCount(1);
		$this->acceptsNoKeyedValues();
	}
	
	/**
	 * The expansion step of After creates a MethodCallWrapper
	 * for each wrappableMethod in the methods listed in the Annotation's
	 * parameters.
	 * 
	 * @param string $class Classname the annotation is applied to.
	 * @param mixed $reflection The Reflection(Class|Method|Property) object the annotation is applied to.
	 * @param ClassDescriptor $descriptor The ClassDescriptor being manipulated.
	 */
	protected function expand($class, $reflection, $descriptor) {
		$methodName = $reflection->getName();
		
		foreach($this->values as $wrappableMethod) {
			$wrapper = new MethodCallWrapper();
			$wrapper->addCallAfter($methodName);
			$descriptor->addWrapper($wrappableMethod, $wrapper);
		}
	}
}
?>