<?php
Library::import('recess.database.pdo.RecessColumnDefinition');

/**
 * RecessTableDefinition represents a basic abstraction of an RDBMS table.
 * @author Kris Jordan
 */
class RecessTableDefinition {
	
	public $name;
	
	protected $columns = array();
	
	function addColumn($name, $type, $nullable = true, $isPrimaryKey = false, $defaultValue = '', $options = array()) {
		$this->columns[$name] = new RecessColumnDefinition($name, $type, $nullable, $isPrimaryKey, $defaultValue, $options);
	}
	
	function getColumns() {
		return $this->columns;
	}
	
}
?>