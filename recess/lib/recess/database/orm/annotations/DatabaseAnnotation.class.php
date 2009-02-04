<?php
Library::import('recess.database.orm.annotations');

/**
 * An annotation used on Model Classes, the Database annotations sets the name
 * of the data source (Databases::getSource($name)) this Model should talk to.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class DatabaseAnnotation extends ModelAnnotation {
	public $source;
	
	function init($array) {
		if(isset($array[0]) && count($array) == 1) {
			$this->source = $array[0];
		} else {
			throw new RecessException('!Database annotation takes 1 parameter: name. Ex: !Database Default', get_defined_vars());
		}
	}
	
	function massage(ModelDescriptor &$descriptor) {
		$descriptor->source = $this->source;
	}
}
?>