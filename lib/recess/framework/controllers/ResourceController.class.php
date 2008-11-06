<?php

Library::import('recess.framework.controllers.AbstractController');
Library::import('recess.http.Request');
Library::import('recess.http.responses.OkResponse');
Library::import('recess.framework.conventions.default.routing.Route');
Library::import('recess.lang.Inflector');

abstract class ResourceController extends AbstractController {
	protected $resourceName;
	
	public function __construct() {
		parent::__construct();
		$this->formats = Formats::$all;
		$this->routes = array_merge($this->routes, $this->generateRoutes());
	}
	
	/**
	 * @route (Methods: GET, Route: $resource)
	 */
	abstract function index(Request $request);
	
	/**
	 * @route (Methods: GET, Route: $resource/:id)
	 */
	abstract function details(Request $request, $id);
	
	/**
	 * @route (Methods: GET, Route: $resource/new)
	 */
	abstract function newForm(Request $request);
	abstract function create(Request $request);
	abstract function editForm(Request $request, $id);
	abstract function update(Request $request, $id);
	abstract function delete(Request $request, $id);
	
	private function generateRoutes() {
		$resource = $this->getResourceName();
		$prefix = '/' . $resource;
		$className = get_class($this);
		return array(
						new Route($className, 'index',	Methods::GET, 	$prefix ),
						new Route($className, 'details',Methods::GET, 	$prefix . '/:id'),
						new Route($className, 'newForm',Methods::GET, 	$prefix . '/new'),
						new Route($className, 'create',	Methods::POST,	$prefix ),
						new Route($className, 'editForm',Methods::GET, 	$prefix . '/edit/:id'),
						new Route($className, 'update',	Methods::PUT, 	$prefix . '/:id'),
						new Route($className, 'delete',	Methods::DELETE,$prefix . '/:id')
					);
	}
	
	public function serve(Request $request) {
		$request->meta['resource'] = $this->getResourceName();
		return parent::serve($request);
	}
	
	protected final function getResourceName() {
		if(!isset($this->resourceName)) {
			$this->resourceName = Inflector::toUnderscores(str_replace('Controller', '', get_class($this)));
		}
		return $this->resourceName;
	}
	
	protected function okOr404ResponseIfNull($request, $result) {
		if($result !== null) {
			return new OkResponse($request, array(get_class($result) => $result));
		} else {
			return new NotFoundResponse($request);
		}
	}	
}

?>