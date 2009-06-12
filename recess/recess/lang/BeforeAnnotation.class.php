<?php
Library::import('recess.lang.Annotation');
Library::import('recess.lang.MethodCallWrapper');

/**
 * The Before annotation can be used as a shortcut for registering
 * methods that should be called before a wrapped method. It is placed
 * on the method to be called and referencess the wrappable method.
 * 
 * For example, on a subclass of Model: 
 * /** !Before create, insert *_/
 * function validate() { echo "validate"; }
 * 
 * There are a couple of nuances worth noting:
 * 
 * -Your Before method must take the same arguments as the wrapped method.
 * -If your method returns a non-null value it will short-circuit the call
 *  and immediately return your returned value without calling the wrapped
 *  method.
 *  
 *  @author Kris Jordan
 *  @since 0.20
 */
class BeforeAnnotation extends Annotation {
	
	/**
	 * Returns a string representation of the intended usage of an annotation.
	 * 
	 * @return string
	 */
	public function usage() {
		return '!Before wrappableMethod[, wrappableMethod]*';
	}
	
	/**
	 * Before is only used on methods.
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
	 * The expansion step of Before creates a MethodCallWrapper
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
			$wrapper->addCallBefore($methodName);
			$descriptor->addWrapper($wrappableMethod, $wrapper);
		}
	}
}
?>