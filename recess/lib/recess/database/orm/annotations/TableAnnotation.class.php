<?php
Library::import('recess.database.orm.annotations');

/**
 * An annotation used on Model Classes, the Table annotations links a model
 * to a table in the RDBMS.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class TableAnnotation extends ModelAnnotation {
	public $table;
	
	function init($array) {
		if(isset($array[0]) && count($array) == 1) {
			$this->table = $array[0];
		} else {
			throw new RecessException('!Table annotation takes 1 parameter: table name. Ex: !Table my_table', get_defined_vars());
		}
	}
	
	function massage(ModelDescriptor &$descriptor) {
		if(isset($this->table)) {
			$descriptor->setTable($this->table);
		}
	}
}
?>