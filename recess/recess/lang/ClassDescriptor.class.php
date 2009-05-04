<?php
Library::import('recess.lang.AttachedMethod');
Library::import('recess.lang.WrappedMethod');

/**
 * Recess PHP Framework class info object that stores additional
 * state about a Object. This additional state includes
 * attached methods or named public properties.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 */
class ClassDescriptor {
	
	protected $attachedMethods = array();
	protected $wrappedMethods = array();
	
	/**
	 * Return a RecessAttachedMethod for given name, or return false.
	 *
	 * @param string $methodName Method name.
	 * @return RecessAttachedMethod on success, false on failure.
	 */
	function getAttachedMethod($methodName) {
		if(isset($this->attachedMethods[$methodName]))
			return $this->attachedMethods[$methodName];
		else
			return false;
	}
	
	/**
	 * Return all attached methods.
	 *
	 * @return array(AttachedMethod)
	 */
	function getAttachedMethods() {
		return $this->attachedMethods;
	}
	
	/**
	 * Add an attached method with given methodName alias.
	 *
	 * @param string $methodName
	 * @param AttachedMethod $attachedMethod
	 */
	function addAttachedMethod($methodName, AttachedMethod $attachedMethod) {
		$this->attachedMethods[$methodName] = $attachedMethod;
	}
	
	/**
	 * Attach a method to a class. The result of this static method is the ability to
	 * call, on any instance of $attachOnClassName, a method named $attachedMethodAlias
	 * which delegates that method call to $providerInstance's $providerMethodName.
	 *
	 * @param string $attachOnClassName
	 * @param string $attachedMethodAlias
	 * @param object $providerInstance
	 * @param string $providerMethodName
	 */
	function attachMethod($attachOnClassName, $attachedMethodAlias, $providerInstance, $providerMethodName) {
		$attachedMethod = new AttachedMethod($providerInstance, $providerMethodName, $attachedMethodAlias);
		$this->addAttachedMethod($attachedMethodAlias, $attachedMethod);
	}
	
	/**
	 * Add a Wrapper to a WrappedMethod on this class descriptor.
	 * 
	 * @param string $methodName
	 * @param IWrapper $wrapper
	 */
	function addWrapper($methodName, IWrapper $wrapper) {
		if(!isset($this->wrappedMethods[$methodName])) {
			$this->wrappedMethods[$methodName] = new WrappedMethod();			
		}
		$this->wrappedMethods[$methodName]->addWrapper($wrapper);
	}
	
	/**
	 * Register a WrappedMethod on this class descriptor.
	 * 
	 * @param string $methodName
	 * @param WrappedMethod $wrappedMethod
	 */
	function addWrappedMethod($methodName, WrappedMethod $wrappedMethod) {
		if(isset($this->wrappedMethods[$methodName])) {
			$this->wrappedMethods[$methodName] = $wrappedMethod->assume($this->wrappedMethods[$methodName]);
		} else {
			$this->wrappedMethods[$methodName] = $wrappedMethod;
		}
	}
}
?>