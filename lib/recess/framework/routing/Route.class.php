<?php

/**
 * Routes map a routing path to a application, class, and method.
 * 
 * @author Kris Jordan <kris@krisjordan.com>
 * @copyright Copyright (c) 2008, Kris Jordan 
 * @package recess.routing
 */
class Route {
	public $class;
	public $function;
	
	public $methods = array();
	public $path;
	public $args = array();
	
	public function __construct($class, $function, $methods, $path) {
		$this->class = $class;
		$this->function = $function;
				
		if(is_array($methods)) { $this->methods = $methods; }
		else { $this->methods[] = $methods; }
		$this->path = $path;
	}
}

?>