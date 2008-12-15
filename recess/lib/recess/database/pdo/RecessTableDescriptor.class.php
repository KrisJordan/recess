<?php
Library::import('recess.database.pdo.RecessColumnDescriptor');

/**
 * RecessTableDescriptor represents a basic abstraction of an RDBMS table.
 * 
 * @author Kris Jordan
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
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