<?php
namespace recess\lang;

/**
 * Recess Object class info data structure that stores
 * meta-data and state for subclasses of Object. This additional 
 * state includes attached methods, wrapped methods, etc.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class ClassDescriptor {
	
	protected $attachedMethods = array();
	
	/**
	 * Attach a method to a class. The result of this static method is the ability to
	 * call, on any instance of $attachOnClassName, a method named $attachedMethodAlias
	 * which delegates that method call to $callable.
	 *
	 * @param string $attachedMethodAlias
	 * @param callable $callable
	 */
	function attach($alias, $callable) {
		$attachedMethod = new AttachedMethod($alias, $callable);
		$this->attachedMethods[$alias] = $attachedMethod;
		return $callable;
	}
	
	/**
	 * Return the callable of an attached method for given name or false.
	 *
	 * @param string $methodName Method name.
	 * @return RecessAttachedMethod on success, false on failure.
	 */
	function attached($methodName) {
		if(isset($this->attachedMethods[$methodName]))
			return $this->attachedMethods[$methodName]->callable;
		else
			return false;
	}
	
	/**
	 * Return a RecessAttachedMethod for given name or false.
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

}