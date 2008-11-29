<?php
Library::import('recess.lang.RecessObject');
Library::import('recess.lang.RecessReflectionClass');
Library::import('recess.lang.Annotation', true);
Library::import('recess.framework.annotations.ViewAnnotation', true);
Library::import('recess.framework.annotations.RouteAnnotation', true);
Library::import('recess.framework.annotations.RoutesPrefixAnnotation', true);

/**
 * The controller is responsible for interpretting a preprocessed Request,
 * performing some action in response to the Request (usually CRUDS), and
 * returning a Response which contains relevant state for a view to render
 * the Response.
 * 
 * @author Kris Jordan
 */
abstract class Controller extends RecessObject {
	protected $request;
	protected $headers;
	protected $application;
	
	/** The formats/content-types which a controller responds to. */
	protected $formats = array(Formats::xhtml);
	
	public function __construct($application) {
		$this->application = $application;
	}
	
	public static function getViewClass($class) {
		return self::getClassDescriptor($class)->viewClass;
	}
	
	public static function getViewPrefix($class) {
		return self::getClassDescriptor($class)->viewPrefix;
	}
	
	public static function getRoutes($class) {
		return self::getClassDescriptor($class)->routes;
	}
	
	protected static function buildClassDescriptor($class) {
		$descriptor = new ControllerDescriptor($class);
		
		try {
			$reflection = new RecessReflectionClass($class);
		} catch(ReflectionException $e) {
			throw new RecessException('Class "' . $class . '" has not been declared.', get_defined_vars());
		}
		
		$annotations = $reflection->getAnnotations();
		foreach($annotations as $annotation) {
			if($annotation instanceof ControllerAnnotation) {
				$annotation->massage($class, '', $descriptor);
			}
		}
		
		$reflectedMethods = $reflection->getMethods(false);
		$methods = array();
		foreach($reflectedMethods as $reflectedMethod) {
			$annotations = $reflectedMethod->getAnnotations();
			foreach($annotations as $annotation) {
				if($annotation instanceof ControllerAnnotation) {
					$annotation->massage(Library::getFullyQualifiedClassName($class), $reflectedMethod->name, $descriptor);
				}
			}
		}
		
		return $descriptor;
	}
	
	public function urlToMethod($methodName) {
		$args = func_get_args();
		array_shift($args);
		
		$descriptor = Controller::getClassDescriptor($this);
		if(isset($descriptor->methodUrls[$methodName])) {
			$url = $descriptor->methodUrls[$methodName];
			if($url[0] != '/') {
				$url = $this->application->routingPrefix . $url;
			}
			if($url[0] == '/') {
				$url = substr($url,1);
			}
			
			if(!empty($args)) {
				$reflectedMethod = new ReflectionMethod($this, $methodName);
				$parameters = $reflectedMethod->getParameters();
				
				if(count($parameters) < count($args)) {
					throw new RecessException('urlToMethod(\'' . $methodName . '\') called with ' . count($args) . ' arguments, method "' . $methodName . '" takes ' . count($parameters) . '.', get_defined_vars());
				}
				
				$i = 0;
				$params = array();
				foreach($parameters as $parameter) {
					if(isset($args[$i])) $params[] = '$' . $parameter->getName();
					$i++;
				}
				$url = str_replace($params, $args, $url);
			}
			
			if(strpos($url, '$') !== false) { 
				throw new RecessException('Missing arguments in urlToMethod(' . $methodName . '). Provide values for missing arguments: ' . $url, get_defined_vars());
			}
			
			
			return $_ENV['url.base'] . $url;
		} else {
			throw new RecessException('No url for method ' . $methodName . ' exists.', get_defined_vars());
		}
	}
	
	/**
	 * The serve method is where inversion of control occurs which delegates
	 * control to another method in the controller.
	 * 
	 * The method name and arguments should have been extracted in the 
	 * preprocessing step. Here we ensure that the method exists and that all 
	 * required parameters are provided as arguments from the request string.
	 * 
	 * Call the method and return its response.
	 *
	 * @param DefaultRequest $request The HTTP request being served.
	 * @final
	 */
	final function serve(Request $request) {
		$this->request = $request;
		
		$methodName = $request->meta->controllerMethod;
		$methodArguments = $request->meta->controllerMethodArguments;
		$useAssociativeArguments = $request->meta->useAssociativeArguments;
		
		// Does method exist? Do arguments match?
		if (method_exists($this, $methodName)) {
			$method = new ReflectionMethod($this, $methodName);
			$parameters = $method->getParameters();
			
			$callArguments = array();
			try {
				if($useAssociativeArguments) {
					$callArguments = $this->getCallArgumentsAssociative($parameters, $methodArguments);
				} else {
					$callArguments = $this->getCallArgumentsSequential($parameters, $methodArguments);
				}
			} catch(RecessException $e) {
				throw new RecessException('Error calling method "' . $methodName . '" in "' . get_class($this) . '". ' . $e->getMessage(), array());
			}
			
			$response = $method->invokeArgs($this, $callArguments);
		} else {
			throw new RecessException('Error calling method "' . $methodName . '" in "' . get_class($this) . '". Method does not exist.', array());
		}

		if(!$response instanceof Response) {
			Library::import('recess.http.responses.OkResponse');
			$response = new OkResponse($this->request);
		}
		
		$descriptor = self::getClassDescriptor($this);
		if(!isset($response->meta->viewName)) $response->meta->viewName = $methodName;
		$response->meta->viewClass = $descriptor->viewClass;
		$response->meta->viewPrefix = $descriptor->viewPrefix;
		$response->data = get_object_vars($this);
		$response->data['controller'] = $this;
		if(is_array($this->headers)) { foreach($this->headers as $header) $response->addHeader($header); }
		unset($response->data['request']);
		unset($response->data['headers']);
		unset($response->data['formats']);
		return $response;
	}

	private function getCallArgumentsAssociative($parameters, $arguments) {
		$callArgs = array();
		foreach($parameters as $parameter) {
			if(!isset($arguments[$parameter->getName()])) {
				if(!$parameter->isOptional()) {
					throw new RecessException('Expects ' . count($parameters) . ' arguments, given ' . count($arguments) . ' and missing required parameter: "' . $parameter->name . '"', array());
				}
			} else {
				$callArgs[] = $arguments[$parameter->getName()];
			}
		}
		return $callArgs;
	}

	private function getCallArgumentsSequential($parameters, $arguments) {
		$callArgs = array();
		$parameterCount = count($parameters);
		for($i = 0; $i < $parameterCount; $i++) {
			if(!isset($arguments[$i])) {
				if(!$parameters[$i]->isOptional()) {
					throw new RecessException('Expects ' . count($parameters) . ' arguments, given ' . count($arguments) . ' and missing required parameter # ' . ($i + 1) . ' named: "' . $parameters[$i]->name . '"', array());
				}
			} else {
				$callArgs[] = $arguments[$i];
			}
		}
		return $callArgs;
	}

	protected function ok($viewName = null) {
		Library::import('recess.http.responses.OkResponse');
		$response = new OkResponse($this->request);
		if(isset($viewName)) $response->meta->viewName = $viewName;
		return $response;
	}
	
	protected function forwardOk($forwardedUri) {
		Library::import('recess.http.responses.ForwardingOkResponse');
		return new ForwardingOkResponse($this->request, $forwardedUri);
	}
	
	protected function created($resourceUri, $contentUri = '') {
		Library::import('recess.http.responses.CreatedResponse');
		if($contentUri == '') $contentUri = $resourceUri;
		return new CreatedResponse($this->request, $resourceUri, $contentUri);
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