<?php
Library::import('recess.framework.helpers.blocks.Block');

class HtmlClasses extends Block {
	
	protected $classes;
	
	function __construct() {
		$args = func_get_args();
		$argCount = func_num_args();
		if($argCount == 1) {
			if(is_array($args[0])) {
				$this->classes = $args[0];
			} else if(is_string($args[0])) {
				$this->classes = array($args[0]);
			}
		} else if ($argCount > 1) {
			$this->classes = $args;
		} else {
			$this->classes = array();
		}
	}
	
	function add($class) {
		$this->classes[] = $class;
		$this->classes = array_unique($this->classes);
		return $this;
	}
	
	function remove($class) {
		$this->classes = array_diff($this->classes, array($class));
		return $this;
	}
	
	function __toString() {
		if(!empty($this->classes)) {
			return 'class="' . implode(' ', $this->classes) . '"';
		} else {
			return '';
		}
	}
	
	function draw() {
		echo $this->__toString();
	}
}
?>