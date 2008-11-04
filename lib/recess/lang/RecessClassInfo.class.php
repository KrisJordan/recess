<?php
/**
 * Recess! Framework class info object that stores additional
 * state about a RecessClass. This additional state includes
 * attached methods or named public properties.
 * 
 * @author Kris Jordan
 */
class RecessClassInfo extends stdClass {
	protected $attachedMethods = array();
	
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
	 * Add an attached method with given methodName alias.
	 *
	 * @param string $methodName
	 * @param RecessAttachedMethod $attachedMethod
	 */
	function addAttachedMethod($methodName, RecessAttachedMethod $attachedMethod) {
		$this->attachedMethods[$methodName] = $attachedMethod;
	}
}
?>