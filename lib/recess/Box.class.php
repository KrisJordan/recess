<?php

class Box extends stdClass {
	public function __call($name,$args) {
		if(!empty($args)) {
			$this->$name = $args[0];
		}
		return $this;
	}
//	public function properties() {
//		$properties = array();
//		foreach($this as $key => $value) {
//			$properties[] = $key;
//		}
//		return $properties;
//	}
//	
//	public function copy($source) {
//		foreach($source as $key => $value) {
//			$this->$key = $value;
//		}
//	}
}

?>