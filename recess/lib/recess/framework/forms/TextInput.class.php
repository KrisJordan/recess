<?php

class TextInput {
	public $name;
	public $value;
	public $class;
	public $flash;
	
	function __construct($name, $value, $class, $flash) {
		$this->name = $name;
		$this->value = $value;
		// $this->class = $class;
		$this->flash = $flash;
	}
	
	function render() {
		if($this->flash != '') {
			echo '<p class="input-flash">';
		}
		echo '<input name="', $this->name, '"';
		if($this->value != '') {
			echo ' value="', $this->value, '"';
		}
//		if($this->class != '') {
//			echo ' class="', $this->class, '"';
//		}
		echo ' />';
		if($this->flash != '') {
			echo $this->flash, '</p>';
		}
	}
}

?>