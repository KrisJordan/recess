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
		$data = $response->data;
		extract($response->data);
		// TODO: Set more interesting variables here.
		include_once(Application::getSetting('View.dir') . $response->meta->viewPrefix . $response->meta->viewName . '.php');
	}
}
?>