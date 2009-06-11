<?php
Library::import('recess.database.orm.annotations');

/**
 * An annotation used on Model Classes, the Table annotations links a model
 * to a table in the RDBMS.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class TableAnnotation extends Annotation {
		
	public function usage() {
		return '!Table tableName';
	}

	public function isFor() {
		return Annotation::FOR_CLASS;
	}
	
	protected function validate($class) {
		$this->acceptsNoKeyedValues();
		$this->exactParameterCount(1);
		$this->validOnSubclassesOf($class, Model::CLASSNAME);
	}
	
	protected function expand($class, $reflection, $descriptor) {
		$descriptor->setTable($this->values[0]);
	}
	
}
?>