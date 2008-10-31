<?php
/**
 * Base class for class and method attributes.
 * @author Kris Jordan
 */
abstract class Attribute {
	/**
	 * Initialize the attribute with value array.
	 * @param array The list of parameters.
	 */
	abstract function init($array);
}
?>