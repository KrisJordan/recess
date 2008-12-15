<?php
Library::import('recess.database.orm.annotations');

/**
 * An annotation used on Model Classes, the Database annotations sets the name
 * of the data source (Databases::getSource($name)) this Model should talk to.
 * 
 * @author Kris Jordan
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link http://www.recessframework.org/
 */
class DatabaseAnnotation extends ModelAnnotation {
	public $source;
	
	function init($array) {
		$this->source = $array[0];	
	}
	
	function massage(ModelDescriptor &$descriptor) {
		$descriptor->source = $this->source;
	}
}
?>