<?php
Library::import('recess.sources.db.pdo.RecessColumnDefinition');

/**
 * RecessTableDefinition represents a basic abstraction of an RDBMS table.
 * @author Kris Jordan
 */
class RecessTableDefinition {
	
	public $name;
	
	protected $columns = array();
	
	function addColumn($name, $type, $nullable = true, $primaryKey = false, $defaultValue = '', $options = array()) {
		$this->columns[$name] = new RecessColumnDefinition($name, $type, $nullable, $primaryKey, $defaultValue, $options);
	}
	
	function getColumns() {
		return $this->columns;
	}
	
}
?>