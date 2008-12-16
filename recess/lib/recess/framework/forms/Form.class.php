<?php
Library::import('recess.framework.forms.TextInput');
Library::import('recess.framework.forms.HiddenInput');

class Form {
	protected $name;
	
	public $method;
	public $action;
	public $flash;
	
	function __construct($name) {
		$this->name = $name;
	}
	
	public $hasErrors = false;
	public $inputs = array();
	
	function __get($name) {
		if(isset($this->inputs[$name])) {
			return $this->inputs[$name]->value;
		} else {
			return '';
		}
	}
	
	function __set($name, $value) {
		if(isset($this->inputs[$name])) {
			$this->inputs[$name]->value = $value;
		}
	}
	
	function begin() {
		if($this->method == Methods::DELETE || $this->method == Methods::PUT) {
			echo '<form method="POST" action="' . $this->action . '">';
			echo '<input type="hidden" name="_METHOD" value="' . $this->method . '" />';
		} else {
			echo '<form method="' . $this->method . '" action="' . $this->action . '">';
		}
	}
	
	function input($name) {
		$this->inputs[$name]->render();
	}
	
	function fill(array $keyValues) {
		foreach($this->inputs as $key => $value) {
			if(isset($keyValues[$key])) {
				$this->inputs[$key]->value = $keyValues[$key];
			}
		}
	}
	
	function assertNotEmpty($inputName) {
		if(isset($this->inputs[$inputName]) && $this->inputs[$inputName]->value != '') {
			return true;
		} else {
			$this->inputs[$inputName]->class = 'highlight';
			$this->inputs[$inputName]->flash = 'Required.';
			$this->hasErrors = true;
			return false;
		}
	}
	
	function hasErrors() {
		return $this->hasErrors;
	}
	
	function end() {
		echo '</form>';
	}
}
?>