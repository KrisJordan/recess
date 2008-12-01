<?php

Library::import('recess.framework.Application');

class BlogApplication extends Application {
	
	public $name = 'Blog';
	
	public $modelsPrefix = 'blog.models.';
	
	public $controllersPrefix = 'blog.controllers.';
	
	public $viewsDir = 'blog/views/';
	
	public $routingPrefix = 'blog/';
	
}

?>