<?php
/**
 * Base class for class and method annotations.
 * @author Kris Jordan
 */
abstract class Annotation {
	/**
	 * Initialize the Annotation with value array.
	 * @param array The list of parameters.
	 */
	abstract function init($array);
}
?>