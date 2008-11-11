<?php
Library::import('recess.framework.Application');

class RecessIdeApplication extends Application {
	
	public $controllersPrefix = 'recess.framework.apps.ide.controllers';
	
	public $routingPrefix = '/ide/';
	
}
?>