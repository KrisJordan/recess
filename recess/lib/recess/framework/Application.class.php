<?php
Library::import('recess.framework.controllers.Controller');
Library::import('recess.framework.views.SmartyView');
Library::import('recess.framework.views.NativeView');
Library::import('recess.database.orm.Model');

abstract class Application {
	
	public $name = 'Unnamed Application';
	
	public $controllersPrefix = ''; // OVERRIDE THIS with appname.controllers.
	
	public $modelsPrefix = ''; // OVERRIDE THIS with appname.models.
	
	public $viewsDir = ''; // OVERRIDE THIS with appname/views/
	
	public $routingPrefix = '/';
	
	function addRoutesToRouter(RtNode $router) {
		$classes = Library::findClassesIn($this->controllersPrefix);
		
		foreach($classes as $class) {
			if(Library::classExists($this->controllersPrefix . $class)) {
				$instance = new $class($this);
			} else {
				continue;
			}
			
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
		Library::import($this->controllersPrefix . $controller);
		$returnController = new $controller($this);
		return $returnController;
	}
	
	function getViewsDir() {
		return $this->viewsDir;
	}
	
}
?>