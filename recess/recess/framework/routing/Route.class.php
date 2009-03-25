<?php
/**
 * Routes map a routing path to a application, class, and method.
 * 
 * @author Kris Jordan <krisjordan@gmail.com> <kris@krisjordan.com>
 * @copyright Copyright (c) 2008, Kris Jordan 
 * @package recess.routing
 */
class Route {
	public $class;
	public $function;
	
	public $app;
	public $methods = array();
	public $path;
	
	public $fileDefined = '';
	public $lineDefined = 0;
	
	public function __construct($class, $function, $methods, $path) {
		$this->class = $class;
		$this->function = $function;
				
		if(is_array($methods)) { $this->methods = $methods; }
		else { $this->methods[] = $methods; }
		$this->path = $path;
	}
	
	public static function __set_state($array) {
		$route = new Route($array['class'], $array['function'], $array['methods'], $array['path']);
		$route->app = $array['app'];
		return $route;
	}
}
?>