<?php
abstract class Application {
	
	public $controllersPrefix = 'controllers'; // OVERRIDE THIS
	
	public $routingPrefix = '/';
	
	function addRoutes(RoutingNode $router) {
		$classes = Library::findClassesIn($this->controllersPrefix);
		
		foreach($classes as $class) {
			Library::import($this->controllersPrefix . '.' . $class);
			$instance = new $class;
			if($instance instanceof Controller) {
				foreach(Controller::getRoutes($instance) as $route) {
					$router->addRoute($route, $this->routingPrefix);
				}
			}
		}
		
		return $router;
	}
	
}
?>