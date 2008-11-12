<?php

Library::import('recess.framework.Application');

class BlogApplication extends Application {
	
	public $controllersPrefix = 'blog.controllers.';
	
	public $viewsDir = 'blog/views/';
	
	public $routingPrefix = 'blog';
	
}

?>