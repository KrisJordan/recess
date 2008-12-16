<?php

class FormInput {
	protected $name;
	protected $class;
	protected $value;
	
	function __construct($name, $class, $value) {
		$this->name = $name; 
		$this->class = $class;
		$this->value = $value;
	}
	
	function getValue() {
		return $this->value;		
	}
	
	function setValue($value) {
		$this->value = $value;
	}
	
	abstract function render();
}

?>