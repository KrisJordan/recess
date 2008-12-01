<?php
Library::import('recess.framework.Application');

class BackendApplication extends Application {
	public function __construct() {
		
		$this->name = 'New Media Engine';
		
		$this->viewsDir = $_ENV['dir.apps'] . 'backend/views/';	
		
		$this->modelsPrefix = 'backend.models.';
		
		$this->controllersPrefix = 'backend.controllers.';

		$this->modelsPrefix = 'backend.models.';
		
		$this->routingPrefix = 'control/';
		
	}
}
?>