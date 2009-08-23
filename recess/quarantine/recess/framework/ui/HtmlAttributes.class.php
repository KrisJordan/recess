<?php
Library::import('recess.framework.helpers.blocks.Block');

class HtmlAttributes extends Block {
	
	protected $attrs;
	
	function __construct($attrs = array()) {
		$this->attrs = $attrs;
	}
	
	function set($attr, $value) {
		$this->attrs[$attr] = $value;
		return $this;
	}
	
	function get($attr) {
		if(isset($this->attrs[$attr])) {
			return $this->attrs[$attr];
		} else {
			return '';
		}
	}
	
	function remove($attr) {
		$this->attrs[$attr] = '';
		return $this;
	}
	
	function __toString() {
		$attrString = '';
		foreach($this->attrs as $attr => $value) {
			if($value != '') {
				$attrString .= "$attr=\"$value\" ";
			}
		}
		return $attrString;
	}
	
	function draw() {
		echo $this->__toString();
	}
}
?>