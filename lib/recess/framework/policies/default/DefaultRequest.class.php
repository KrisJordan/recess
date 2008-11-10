<?php
Library::import('recess.http.Request');

class DefaultRequest extends Request {
	public $controllerClass;
	public $controllerMethod;
	public $controllerMethodArguments;
	public $viewClass;
	public $viewMethod;
}
?>