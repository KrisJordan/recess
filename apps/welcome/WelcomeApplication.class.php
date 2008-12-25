<?php
Library::import('recess.framework.Application');

class WelcomeApplication extends Application {
	public function __construct() {
		
		$this->name = 'Welcome to Recess';
		
		$this->viewsDir = $_ENV['dir.apps'] . 'welcome/views/';	
		
		$this->modelsPrefix = 'welcome.models.';
		
		$this->controllersPrefix = 'welcome.controllers.';

		$this->modelsPrefix = 'welcome.models.';
		
		$this->routingPrefix = '/';
		
	}
}
?>