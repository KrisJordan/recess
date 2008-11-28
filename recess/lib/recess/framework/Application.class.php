<?php
Library::import('recess.framework.controllers.Controller');
Library::import('recess.framework.views.SmartyView');
Library::import('recess.framework.views.NativeView');
Library::import('recess.sources.db.orm.Model');

abstract class Application {
	
	public $controllersPrefix = ''; // OVERRIDE THIS with appname.controllers.
	
	public $viewsDir = ''; // OVERRIDE THIS with appname/views/
	
	public $routingPrefix = '/';
	
	function __construct() {
		$this->viewsDir = $_ENV['dir.apps'] . $this->viewsDir;
	}
	
	function addRoutesToRouter(RoutingNode $router) {
		$classes = Library::findClassesIn($this->controllersPrefix);
		
		foreach($classes as $class) {
			Library::import($this->controllersPrefix . $class);
			$instance = new $class;
			if($instance instanceof Controller) {
				$routes = Controller::getRoutes($instance);
				if(!is_array($routes)) continue;
				foreach($routes as $route) {
					$router->addRoute($this, $route, $this->routingPrefix);
				}
			}
		}
		
		return $router;
	}
	
	function getController($controller) {
		return Library::importAndInstantiate($this->controllersPrefix . $controller);
	}
	
	function getViewsDir() {
		return $this->viewsDir;
	}
	
}
?>