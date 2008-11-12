<?php
Library::import('recess.framework.Application');

class BackEndApplication extends Application {
	
	public $controllersPrefix = 'backend.controllers.';
	
	public $viewsDir = 'backend/views/';
	
	public $routingPrefix = 'backend';
	
}
?>