<?php

Library::import('recess.framework.Application');

class NuevoBlogApplication extends Application {

	public $controllersPrefix = 'nuevo.controllers.';
	
	public $viewsDir = 'nuevo/views/';
	
	public $routingPrefix = 'nuevo';
	
}

?>