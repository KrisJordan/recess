<?php

Library::import('recess.framework.interfaces.IPreprocessor');

class DefaultPreprocessor implements IPreprocessor {
	
	/**
	 * Used to pre-process a request.
	 * This may involve extracting information and transforming values. 
	 * For example, Transforming the HTTP method from POST to PUT based on a POSTed field.
	 * 
	 * @param	Request The Request to refine.
	 * @return	Request The refined Request.
	 */
	public function process(Request $request) {
		$request = $this->getHttpMethodFromPost($request);

		$request = $this->getFormatFromResourceString($request);

		if($request->format != Formats::xhtml) {
			$request = $this->reparameterizeForFormat($request);
		}
		
		$router = Application::getRouter();
		
		$routerResult = $router->findRouteFor($request);
		if($routerResult->routeExists) {
			if($routerResult->methodIsSupported) {
				$request->meta['controller'] = $routerResult->route->controller;
				$request->meta['function'] = $routerResult->route->function;
				$request->meta['function_args'] = $routerResult->arguments;		
			} else {
				// TODO: Exception Case - Need a means of shortcutting a "METHOD NOT SUPPORTED" 
			}
		}

		return $request;
	}
	
	/////////////////////////////////////////////////////////////////////////
	// Helper Methods

	const HTTP_METHOD_FIELD = '_METHOD';

	protected function getHttpMethodFromPost(Request $request) {
		if(array_key_exists(self::HTTP_METHOD_FIELD, $request->post)) {
			$request->method = $request->post[self::HTTP_METHOD_FIELD];
			unset($request->post[self::HTTP_METHOD_FIELD]);
			if($request->method == Methods::PUT) {
				$request->put = $request->post;
			}
		}
		return $request;
	}

	protected function getFormatFromResourceString(Request $request) {
		$lastPartIndex = count($request->resourceParts) - 1;
		if($lastPartIndex < 0) return $request;
		
		$lastPart = $request->resourceParts[$lastPartIndex];
		
		$lastDotPosition = strrpos($lastPart, Library::dotSeparator);
		if($lastDotPosition !== false) {
			$substring = substr($lastPart, $lastDotPosition + 1);
			if(in_array($substring, Formats::$all)) {
				$request->format = $substring;
				$request->setResource(substr($request->resource, 0, strrpos($request->resource, Library::dotSeparator)));
			} else {
				$request->format = Formats::xhtml;
			}
		}
		return $request;
	}

	protected function reparameterizeForFormat(Request $request) {
		// TODO: Think about how parameter passing via json/xml/post-vars can be streamlined
		if($request->format == Formats::json) {
			if(array_key_exists('json', $request->post)){
				$request->post = json_decode($request->post['json']);
			}
		} else if ($request->format == Formats::xml) {
			// TODO: XML reparameterization in request transformer
		}
		return $request;
	}
}

?>