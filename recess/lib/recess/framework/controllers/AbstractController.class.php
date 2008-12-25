<?php
Library::import('recess.lang.RecessObject');
Library::import('recess.lang.RecessReflectionClass');
Library::import('recess.lang.Annotation', true);
Library::import('recess.framework.interfaces.IController');
Library::import('recess.framework.controllers.annotations.ViewAnnotation', true);
Library::import('recess.framework.controllers.annotations.RouteAnnotation', true);
Library::import('recess.framework.controllers.annotations.RoutesPrefixAnnotation', true);

/**
 * The controller is responsible for interpretting a preprocessed Request,
 * performing some action in response to the Request (usually CRUDS), and
 * returning a Response which contains relevant state for a view to render
 * the Response.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 */
abstract class AbstractController extends RecessObject implements IController {
	
	public abstract function init();
	
	public static function getViewClass($class) {
		return self::getClassDescriptor($class)->viewClass;
	}
	
	public static function getViewPrefix($class) {
		return self::getClassDescriptor($class)->viewPrefix;
	}
	
	public static function getRoutes($class) {
		return self::getClassDescriptor($class)->routes;
	}

	protected function ok($viewName = null) {
		Library::import('recess.http.responses.OkResponse');
		$response = new OkResponse($this->request);
		if(isset($viewName)) $response->meta->viewName = $viewName;
		return $response;
	}
	
	protected function conflict($viewName) {
		Library::import('recess.http.responses.ConflictResponse');
		$response = new ConflictResponse($this->request);
		$response->meta->viewName = $viewName;
		return $response;
	}
	
	protected function redirect($redirectUri) {
		Library::import('recess.http.responses.TemporaryRedirectResponse');
		$response = new TemporaryRedirectResponse($this->request, $redirectUri);
		return $response;
	}
	
	protected function forwardOk($forwardedUri) {
		Library::import('recess.http.responses.ForwardingOkResponse');
		return new ForwardingOkResponse($this->request, $forwardedUri);
	}
	
	protected function forwardNotFound($forwardUri, $flash = '') {
		Library::import('recess.http.responses.ForwardingNotFoundResponse');
		return new ForwardingNotFoundResponse($this->request, $forwardUri, array('flash' => $flash));
	}
	
	protected function created($resourceUri, $contentUri = '') {
		Library::import('recess.http.responses.CreatedResponse');
		if($contentUri == '') $contentUri = $resourceUri;
		return new CreatedResponse($this->request, $resourceUri, $contentUri);
	}
	
	protected function unauthorized($forwardUri, $realm = '') { 
		Library::import('recess.http.responses.ForwardingUnauthorizedResponse');
		return new ForwardingUnauthorizedResponse($this->request, $forwardUri, $realm);
	}
}

class ControllerDescriptor extends RecessObjectDescriptor {
	public $routes = array();
	public $methodUrls = array();
	public $routesPrefix = '';
	public $viewClass = 'recess.framework.views.NativeView';
	public $viewPrefix = '';
}

?>