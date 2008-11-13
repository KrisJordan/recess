<?php
Library::import('recess.framework.views.AbstractView');

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
			case Formats::json:
				foreach($response->data as $key => $value) {
					if($value instanceof ModelSet) {
						$response->data[$key] = $value->toArray();
					}
				}
				print json_encode($response->data);
				exit;
			default:
		}
		
		extract($response->data);
		include_once($response->meta->viewDir . $response->meta->viewName . '.php');
	}
}
?>