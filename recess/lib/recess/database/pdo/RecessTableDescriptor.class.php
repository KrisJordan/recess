<?php
Library::import('recess.database.pdo.RecessColumnDescriptor');

/**
 * RecessTableDescriptor represents a basic abstraction of an RDBMS table.
 * @author Kris Jordan
 */
class RecessTableDescriptor {
	
	public $name;
	
	public $tableExists = false;
	
	protected $columns = array();
	
	function addColumn($name, $type, $nullable = true, $isPrimaryKey = false, $defaultValue = '', $options = array()) {
		$this->columns[$name] = new RecessColumnDescriptor($name, $type, $nullable, $isPrimaryKey, $defaultValue, $options);
	}
	
	function getColumns() {
		return $this->columns;
	}
	
}
?>