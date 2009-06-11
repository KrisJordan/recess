<?php
Library::import('recess.framework.controllers.Controller');
Library::import('recess.framework.views.RecessView');
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
	 * OVERRIDE THIS with the path to your app's public files relative to url.base, apps/appname/public/ by default
	 *
	 * @var string
	 */
	public $assetUrl = '';
	
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
	
	function getAssetUrl() {
		return $this->assetUrl;
	}
	
	function urlTo($methodName) {
		$args = func_get_args();
		list($controllerName, $methodName) = explode('::', $methodName, 2);
		$args[0] = $methodName;
		Library::import($this->controllersPrefix . $controllerName);
		$controller = new $controllerName($this);
		return call_user_func_array(array($controller,'urlTo'),$args);
	}	
}
?>