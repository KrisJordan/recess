<?php
Library::import('recess.framework.Application');

class RecessToolsApplication extends Application {

	public $controllersPrefix = 'recess.apps.tools.controllers.';
	
	public $viewsDir = '';
	
	public $routingPrefix = 'recess/';
	
	public function __construct() {
		$this->viewsDir = $_ENV['dir.recess'] . '/apps/tools/views/';	
	}

}
?>