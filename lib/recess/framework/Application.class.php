<?php
abstract class Application {
	
	public $controllersPrefix = 'controllers.'; // OVERRIDE THIS
	
	public $routingPrefix = '/';
	
	function addRoutesToRouter(RoutingNode $router) {
		$classes = Library::findClassesIn($this->controllersPrefix);
		
		foreach($classes as $class) {
			Library::import($this->controllersPrefix . $class);
			$instance = new $class;
			if($instance instanceof Controller) {
				foreach(Controller::getRoutes($instance) as $route) {
					$router->addRoute($route, $this->routingPrefix);
				}
			}
		}
		
		return $router;
	}
	
	function getController($controller) {
		return Library::importAndInstantiate($this->controllersPrefix . $controller);
	}
	
	function getView($view) {
		$view = Library::importAndInstantiate($view);
		return $view;
	}
	
}
?>