<?php
Library::import('recess.framework.Application');

class FrontEndApplication extends Application {
	
	public $controllersPrefix = 'frontend.controllers';
	
	public $routingPrefix = '/';
	
}
?>