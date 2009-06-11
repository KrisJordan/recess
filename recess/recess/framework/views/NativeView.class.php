<?php
Library::import('recess.framework.AbstractView');

class NativeView extends AbstractView {	
	
	protected function render($format, Response $response) {
		if($format == 'html') {
			$extension = '.php';
		} else {
			$extension = ".$format.php";
		}
		
		$viewTemplate = $response->meta->viewDir . $response->meta->viewName . $extension;
		if(file_exists($viewTemplate)) {
			extract($response->data);
			$viewsDir = $response->meta->app->getViewsDir();
			include($viewTemplate);
			return true;
		} else {
			return false;
		}
	}
	
//	protected function render_JSON(Response $response) {
//		foreach($response->data as $key => $value) {
//			if($value instanceof ModelSet) {
//				$response->data[$key] = $value->toArray();
//			}
//			if($value instanceof Form) {
//				unset($response->data[$key]);
//			}
//			if(substr($key,0,1) == '_') {
//				unset($response->data[$key]);
//			}
//		}
//		if(isset($response->data['application'])) unset ($response->data['application']);
//		if(isset($response->data['controller'])) unset ($response->data['controller']);
//		print json_encode($response->data);
//		exit;
//	}
}
?>