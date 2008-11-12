<?php
Library::import('recess.framework.Application');

class RecessIdeApplication extends Application {

	public $controllersPrefix = 'recess.apps.ide.controllers.';
	
	public $viewsDir = '';
	
	public $routingPrefix = 'recess';
	
	public function __construct() {
		$this->viewsDir = $_ENV['dir.recess'] . '/apps/ide/views/';	
	}

}
?>