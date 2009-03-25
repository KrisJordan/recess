<?php
Library::import('recess.framework.controllers.Controller');
Library::import('recess.framework.views.SmartyView');
Library::import('recess.framework.views.NativeView');
Library::import('recess.database.orm.Model');

abstract class Application {
	
	public $name = 'Unnamed Application';
	
	/**
	 * OVERRIDE THIS with appname.controllers.
	 *
	 * @var string
	 */
	public $controllersPrefix = '';
	
	/**
	 * OVERRIDE THIS with appname.models.
	 *
	 * @var string
	 */
	public $modelsPrefix = '';
	
	/**
	 * OVERRIDE THIS with appname/views/
	 *
	 * @var string
	 */
	public $viewsDir = '';
	
	/**
	 * OVERRIDE THIS with the routing prefix to your application
	 *
	 * @var unknown_type
	 */
	public $routingPrefix = '/';
	
	function addRoutesToRouter(RtNode $router) {
		$classes = $this->listControllers();
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
	
	const CONTROLLERS_CACHE_KEY = 'Recess::Framework::App::LstCntrlrs::';
	
	function listControllers() {
		$controllers = Cache::get(self::CONTROLLERS_CACHE_KEY . get_class($this));
		if($controllers === false) {
			$controllers = Library::findClassesIn($this->controllersPrefix);
		}
		return $controllers;
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