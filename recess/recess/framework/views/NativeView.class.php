<?php
Library::import('recess.framework.AbstractView');

class NativeView extends AbstractView {	
	/**
	 * Realizes HTTP's body content based on the Response parameter. Responsible
	 * for returning content in the format desired. The render method likely uses
	 * inversion of control which delegates to another method within the view to 
	 * realize the Response.
	 *
	 * @param Response $response
	 * @abstract 
	 */
	protected function render(Response $response) {
		switch($response->request->format) {
			case Formats::JSON:
				foreach($response->data as $key => $value) {
					if($value instanceof ModelSet) {
						$response->data[$key] = $value->toArray();
					}
					if($value instanceof Form) {
						unset($response->data[$key]);
					}
					if(substr($key,0,1) == '_') {
						unset($response->data[$key]);
					}
				}
				if(isset($response->data['application'])) unset ($response->data['application']);
				if(isset($response->data['controller'])) unset ($response->data['controller']);
				print json_encode($response->data);
				exit;
			default:
		}
		
		extract($response->data);
		$viewsDir = $response->meta->app->getViewsDir();
				
		include_once($response->meta->viewDir . $response->meta->viewName . '.php');
	}
}
?>