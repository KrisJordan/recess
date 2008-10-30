<?php

/**
 * "Anonymous" class which does not require predefined public member variables.
 * 
 * @author Kris Jordan
 * @author Joel Sutherland
 * 
 * @package recess
 */
class Box extends stdClass {
	
	/**
	 * Member variable assignment through function calls which allow for chaining.
	 * i.e. $box->foo('bar')->fooz->('baz'); // $box->foo == 'bar', $box->bax == 'baz'
	 *
	 * @param string $name Name of member property.
	 * @param array $args Single valued array.
	 * @return Box Self-referencing Box to allow for assignment chaining.
	 * 
	 * @todo Decide on whether this makes it in release. Kris is uneasy about having two 
	 * 		 means for assignment and abusing what it means to call a method/function.
	 */
	public function __call($name,$args) {
		if(!empty($args)) {
			$this->$name = $args[0];
		}
		return $this;
	}
	
}

?>