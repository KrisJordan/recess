<?php
Library::import('recess.framework.controllers.Controller');
Library::import('recess.database.orm.Model');
Library::import('recess.lang.PathFinder');

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
	 * @var PathFinder
	 */
	public $viewsDir = null;
	
	/**
	 * OVERRIDE THIS with the path to your app's public files relative to url.base, apps/appname/public/ by default
	 *
	 * @var string
	 */
	public $assetUrl = '';
	
	/**
	 * OVERRIDE THIS with the routing prefix to your application
	 *
	 * @var string
	 */
	public $routingPrefix = '/';
	
	public $plugins = array();
	
	protected $viewPathFinder = null;
	
	public function addViewPath($path) {
		if($this->viewPathFinder == null) {
			$this->viewPathFinder = new PathFinder();
		}
		$this->viewPathFinder->addPath($path);
	}
	
	public function viewPathFinder() {
		return $this->viewPathFinder;
	}
	
	public function findView($view) {
		return $this->viewPathFinder->find($view);
	}
	
	static protected $runningApplication = null;
	
	static function active() {
		return self::$runningApplication;
	}
	
	static function activate(Application $application) {
		$application->init();
		self::$runningApplication = $application;	
	}
	
	function init() {
		$this->addViewPath($_ENV['dir.recess'] . 'recess/framework/ui/parts/');
		foreach($this->plugins as $plugin) {
			$plugin->init($this);
		}
		$this->addViewPath($this->viewsDir);
	}
	
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

	/** 
	 * Deprecated. Use findView instead.
	 * @return unknown_type
	 */
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
