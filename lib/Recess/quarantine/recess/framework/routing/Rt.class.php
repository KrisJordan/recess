<?php
class Rt {
	public $c; // Class
	public $f; // Function
	public $a; // App
	
	function __construct(Route $route) {
		$this->c = Library::getClassName($route->class);
		$this->f = $route->function;
		$this->a = $route->app;
	}
	
	function toRoute() {
		$route = new Route(Library::getFullyQualifiedClassName($this->c),$this->f,array(),'');
		$route->app = $this->a;
		return $route;
	}
}
?>