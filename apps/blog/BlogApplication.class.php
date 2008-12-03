<?php
Library::import('recess.framework.Application');

class BlogApplication extends Application {
	public function __construct() {
		
		$this->name = 'Blog';
		
		$this->viewsDir = $_ENV['dir.apps'] . 'blog/views/';	
		
		$this->modelsPrefix = 'blog.models.';
		
		$this->controllersPrefix = 'blog.controllers.';

		$this->modelsPrefix = 'blog.models.';
		
		$this->routingPrefix = 'blog/';
		
	}
	
	public static function __set_state($array) {
		$app = new BlogApplication();
		$app->name = $array['name'];
		$app->controllersPrefix = $array['controllersPrefix'];
		$app->modelsPrefix = $array['modelsPrefix'];
		$app->viewsDir = $array['viewsDir'];
		$app->routingPrefix = $array['routingPrefix'];
		return $app;
	}
}
?>