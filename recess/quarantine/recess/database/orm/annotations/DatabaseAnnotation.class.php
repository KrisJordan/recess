<?php
Library::import('recess.database.orm.annotations');

/**
 * An annotation used on Model Classes, the Database annotations sets the name
 * of the data source (Databases::getSource($name)) this Model should talk to.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class DatabaseAnnotation extends Annotation {
	
	public function usage() {
		return '!Database databaseName';
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
		$descriptor->source = $this->values[0];
	}
	
}
?>