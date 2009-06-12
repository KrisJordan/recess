<?php
Library::import('recess.framework.AbstractView');

class NativeView extends AbstractView {
	protected function getTemplateFor($response) {
		// TODO: Cache in production mode
		$format = $response->request->accepts->format();
		
		if($format == 'html' || $format == '') {
			$extension = '.php';
		} else {
			$extension = ".$format.php";
		}
		
		return $response->meta->app->getViewsDir() 
				. $response->meta->viewsPrefix 
				. $response->meta->viewName 
				. $extension;
	}
	
	public function canRespondWith(Response $response) {
		return file_exists($this->getTemplateFor($response));
	}
	
	protected function render(Response $response) {
		extract($response->data);
		$viewsDir = $response->meta->app->getViewsDir();
		include $this->getTemplateFor($response);
	}
}
?>