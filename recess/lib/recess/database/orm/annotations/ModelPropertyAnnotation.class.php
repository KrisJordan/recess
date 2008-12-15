<?php
Library::import('recess.lang.Annotation');

/**
 * Abstract class for annotations used on the properties of a model.
 * 
 * @author Kris Jordan
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link http://www.recessframework.org/
 */
abstract class ModelPropertyAnnotation extends Annotation {
	
	abstract function massage(ModelProperty $property);
	
}

?>