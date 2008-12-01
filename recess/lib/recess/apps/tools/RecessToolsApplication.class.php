<?php
Library::import('recess.framework.Application');

class RecessToolsApplication extends Application {
	
	public $codeTemplatesDir;
	
	public function __construct() {
		
		$this->name = 'Recess Tools';
		
		$this->viewsDir = $_ENV['dir.recess'] . 'apps/tools/views/';	
		
		$this->codeTemplatesDir = $_ENV['dir.recess'] . 'apps/tools/templates/';
		
		$this->modelsPrefix = 'recess.apps.tools.models.';
		
		$this->controllersPrefix = 'recess.apps.tools.controllers.';

		$this->modelsPrefix = 'recess.apps.tools.models.';
		
		$this->routingPrefix = 'recess/';
		
	}
}

?>