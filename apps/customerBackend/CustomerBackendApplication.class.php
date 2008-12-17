<?php
Library::import('recess.framework.Application');

class CustomerBackendApplication extends Application {
	public function __construct() {
		
		$this->name = 'Customer Backend';
		
		$this->viewsDir = $_ENV['dir.apps'] . 'customerBackend/views/';	
		
		$this->modelsPrefix = 'customerBackend.models.';
		
		$this->controllersPrefix = 'customerBackend.controllers.';

		$this->modelsPrefix = 'customerBackend.models.';
		
		$this->routingPrefix = 'customerBackend/';
		
	}
}
?>