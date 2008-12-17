<?php
abstract class FormInput {
	protected $name;
	public $class;
	public $value;
	
	function __construct($name) {
		$this->name = $name;
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