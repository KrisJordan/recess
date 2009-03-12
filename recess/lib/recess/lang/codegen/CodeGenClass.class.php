<?php
/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class CodeGenClass {
	
	protected $doccomment;
	protected $name;
	protected $properties = array();
	protected $methods = array();
	protected $extends = '';
	
	function __construct($className) {
		$this->name = $className;
	}
	
	function setName($name) {
		$this->name = $name;
	}
	
	function setExtends($extends) {
		$this->extends = $extends;
	}
	
	function addProperty(CodeGenProperty $property) {
		$this->properties[] = $property;
	}
	
	function addMethod(CodeGenMethod $method) {
		$this->methods[] = $method;
	}
	
	function setDocComment(CodeGenDocComment $docComment) {
		$this->doccomment = $docComment;
	}
	
	function toCode($blockIndent = '') {
		$code = $this->doccomment->toCode();
		
		$code .= 'class ' . $this->name;

		if($this->extends != '') {
			$code .= ' extends ' . $this->extends;
		}
		
		$code .= ' {' . CodeGen::NL;
		
		foreach($this->properties as $property) {
			$code .= $property->toCode(CodeGen::TAB) . CodeGen::NL;
		}
		
		foreach($this->methods as $method) {
			$code .= $method->toCode(CodeGen::TAB) . CodeGen::NL;
		}
		
		$code .= '}' . CodeGen::NL;
		return $code;	
	}
	
}

?>