<?php
Library::import('recess.lang.Annotation');

/**
 * Abstract class for annotations used on the properties of a model.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
abstract class ModelPropertyAnnotation extends Annotation {
	
	abstract function massage(ModelProperty $property);
	
}

?>