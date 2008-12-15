<?php
Library::import('recess.database.orm.annotations');

/**
 * An annotation used on Model Classes, the Table annotations links a model
 * to a table in the RDBMS.
 * 
 * @author Kris Jordan
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link http://www.recessframework.org/
 */
class TableAnnotation extends ModelAnnotation {
	public $table;
	
	function init($array) {
		$this->table = $array[0];	
	}
	
	function massage(ModelDescriptor &$descriptor) {
		$descriptor->setTable($this->table);
	}
}
?>