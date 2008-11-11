<?php
Library::import('recess.framework.Application');

class BackEndApplication extends Application {
	
	public $controllersPrefix = 'backend.controllers.';
	
	public $routingPrefix = 'backend';
	
}
?>