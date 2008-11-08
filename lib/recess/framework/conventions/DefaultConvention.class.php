<?php
Library::import('recess.framework.interfaces.IConvention');
Library::import('recess.lang.Inflector');
Library::import('recess.framework.controllers.PluggableController');
Library::import('recess.framework.controllers.ErrorController');
Library::import('recess.framework.conventions.default.DefaultPreprocessor');
Library::import('recess.framework.views.ErrorView');

class DefaultConvention implements IConvention {

	public $applicationControllersPrefix = 'app.controllers.';
	public $applicationViewsPrefix = 'app.views.';

	protected $plugins = array();
	
	public function __construct($plugins) {
		$this->plugins = $plugins;
	}
	
	public function getPreprocessor() {
		return new DefaultPreprocessor();	
	}

	public function getControllerFor(Request $request) {
		// TODO: Add support for plugins!
		// TODO: Make Request => StandardRequest and add fields for controller, function, function_args
		if(isset($request->meta['controller'])) {
			// The preprocessor's routing has picked a controller.
			return new $request->meta['controller'];
		} else {
			// Standard convention, otherwise, is to try and find a controller
			// based on the first word of the resource string + "Controller"
			// i.e.: /pages/1 => PagesController
			if(isset($request->resourceParts[0])) {
				$className = $this->applicationControllersPrefix . Inflector::toProperCaps($request->resourceParts[0]) . 'Controller';
				echo $className;
				if(Library::classExists($className)) {
					$className = Library::getClassName($className);
					return new $className;
				} else {
					return new ErrorController();
				}
			} else {
				// Home controller is the default controller
				if(Library::classExists($this->applicationControllersPrefix . 'HomeController')) {
					return new HomeController();
				} else {
					return new ErrorController();
				}
			}
		}
//			$pluggableController = new PluggableController($controller);
//			foreach($this->plugins as $plugin) {
//				Library::import($plugin);
//				$pluginClassName = Library::getClassName($plugin); 
//				$instance = new $pluginClassName;
//				$pluggableController->addPlugin($instance);
//			}
//			return $pluggableController;
	}

	public function getViewFor(Response $response) {
		// TODO: This needs significant refactoring.
		if($response instanceof ErrorResponse) {
			if(Application::isInDebugMode()) {
				throw new RecessTraceException(ResponseCodes::getMessageForCode($response->code), $response->trace);
			} else {
				return new ErrorView();
			}
		}
		
		if(isset($response->meta['viewClass'])) {
			$className = $response->meta['viewClass'];
			if(Library::classExists($this->applicationViewsPrefix . $className)) {
				Library::import($this->applicationViewsPrefix . $className);
				return new $className;
			} else {
				throw new RecessException('Cannot locate view, expecting: ' . $this->applicationViewsPrefix . $className, get_defined_vars());
			}
		}
		
		if(isset($response->meta['resource'])) {
			$className = Inflector::toProperCaps($response->meta['resource']) . 'View';
			if(Library::classExists($this->applicationViewsPrefix . $className)) {
				Library::import($this->applicationViewsPrefix . $className);
				return new $className;
			} else {
				throw new RecessException('Cannot locate view, expecting: ' . $this->applicationViewsPrefix . $className, get_defined_vars());
			}
		} else {
			return new ErrorView();
		}
	}
}


?>