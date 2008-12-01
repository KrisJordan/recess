<?php

class HiddenInput {
	public $name;
	public $value;
	
	function __construct($name, $value) {
		$this->name = $name;
		$this->value = $value;
	}
	
	function render() {
		echo '<input type="hidden" name="', $this->name, '"';
		if($this->value != '') {
			echo ' value="', $this->value, '"';
		}
		echo ' />';
	}
}

?>