<?php

/**
 * RecessTable represents a basic abstraction of an RDBMS column.
 * @author Kris Jordan
 */
class RecessColumnDescriptor {
	
	public $name;
	
	public $type;
	
	public $isPrimaryKey = false;
	
	public $nullable = true;
	
	public $defaultValue = '';
	
	public $options = array();
	
	function __construct($name, $type, $nullable = true, $isPrimaryKey = false, $defaultValue = '', $options = array()) {
		$this->name = $name;
		$this->type = $type;
		$this->isPrimaryKey = $isPrimaryKey;
		$this->nullable = $nullable;
		$this->defaultValue = $defaultValue;
		$this->options = $options;
	}
	
}

?>