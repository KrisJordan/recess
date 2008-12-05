<?php

abstract class CodeGenClassMember {
	protected $name;
	protected $doccomment;
	protected $accessLevel = CodeGen::PUB;
	protected $isStatic = false;
	
	function __construct($name, $accessLevel = CodeGen::PUB, $isStatic = false) {
		$this->name = $name;
		$this->accessLevel = $accessLevel;
		$this->isStatic = $isStatic;
	}
	
	function setName($name) {
		$this->name = $name;
	}
	
	function setAccessLevel($accessLevel) {
		$this->accessLevel = $accessLevel;
	}
	
	function setStatic() {
		$this->isStatic = true;
	}
	
	function setDocComment(CodeGenDocComment $docComment) {
		$this->doccomment = $docComment;
	}
	
	abstract function toCode($blockIndent = '');
}

?>