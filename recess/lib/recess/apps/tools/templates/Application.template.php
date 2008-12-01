<?php
Library::import('recess.framework.Application');

class {{programmaticName}}Application extends Application {
	public function __construct() {
		
		$this->name = '{{appName}}';
		
		$this->viewsDir = $_ENV['dir.apps'] . '{{camelProgrammaticName}}/views/';	
		
		$this->modelsPrefix = '{{camelProgrammaticName}}.models.';
		
		$this->controllersPrefix = '{{camelProgrammaticName}}.controllers.';

		$this->modelsPrefix = '{{camelProgrammaticName}}.models.';
		
		$this->routingPrefix = '{{routesPrefix}}';
		
	}
}
?>