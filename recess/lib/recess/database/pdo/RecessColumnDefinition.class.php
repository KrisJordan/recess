<?php

/**
 * RecessTable represents a basic abstraction of an RDBMS column.
 * @author Kris Jordan
 */
class RecessColumnDefinition {
	
	public $name;
	
	public $type;
	
	public $primaryKey = false;
	
	public $nullable = true;
	
	public $defaultValue = '';
	
	public $options = array();
	
	function __construct($name, $type, $nullable = true, $primaryKey = false, $defaultValue = '', $options = array()) {
		$this->name = $name;
		$this->type = $type;
		$this->primaryKey = $primaryKey;
		$this->nullable = $nullable;
		$this->defaultValue = $defaultValue;
		$this->options = $options;
	}
	
}

?>