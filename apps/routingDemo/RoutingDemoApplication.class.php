<?php
Library::import('recess.framework.Application');

class RoutingDemoApplication extends Application {
	public function __construct() {
		
		$this->name = 'Routing Demo';
		
		$this->viewsDir = $_ENV['dir.apps'] . 'routingDemo/views/';	
		
		$this->modelsPrefix = 'routingDemo.models.';
		
		$this->controllersPrefix = 'routingDemo.controllers.';

		$this->modelsPrefix = 'routingDemo.models.';
		
		$this->routingPrefix = 'routes/';
		
	}
}
?>