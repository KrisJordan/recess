<?php
Library::import('recess.framework.Application');

class TestGenApplication extends Application {
	public function __construct() {
		
		$this->name = 'TestGen';
		
		$this->viewsDir = $_ENV['dir.apps'] . 'testGen/views/';	
		
		$this->modelsPrefix = 'testGen.models.';
		
		$this->controllersPrefix = 'testGen.controllers.';

		$this->modelsPrefix = 'testGen.models.';
		
		$this->routingPrefix = 'testGen/';
		
	}
}
?>