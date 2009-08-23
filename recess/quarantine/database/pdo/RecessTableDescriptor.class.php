<?php
Library::import('recess.database.pdo.RecessColumnDescriptor');

/**
 * RecessTableDescriptor represents a basic abstraction of an RDBMS table.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
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