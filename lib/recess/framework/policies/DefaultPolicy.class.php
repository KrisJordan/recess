<?php

Library::import('recess.framework.policies.default.DefaultPreprocessor');
Library::import('recess.framework.policies.default.DefaultRequest');
Library::import('recess.framework.policies.default.DefaultResponse');
Library::import('recess.framework.policies.default.Controller');
Library::import('recess.framework.policies.default.View');
Library::import('recess.framework.interfaces.IPolicy');

class DefaultPolicy implements IPolicy {
	
	public function getPreprocessor() {
		return new DefaultPreprocessor();	
	}
	
	public function getControllerFor(Request $request) {
		Application::loadController($request->meta->controllerClass);
		return new $request->meta->controllerClass;		
	}
	
	public function getViewFor(Response $response) {
		if(Library::classExists('recess.framework.policies.default.' . $response->meta->viewClass)) {
			return new $response->meta->viewClass;
		} else {
			Application::loadView($response->meta->viewClass);
			return new $response->meta->viewClass;
		}
	}
}

?>