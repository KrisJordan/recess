<?php
Library::import('recess.framework.views.NativeView');
Library::import('recess.framework.helpers.Layout');
Library::import('recess.framework.helpers.Url');
Library::import('recess.framework.helpers.Html');

class RecessView extends NativeView { 	
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
		$this->loadHelper(
						'recess.framework.helpers.Layout',
						'recess.framework.helpers.Part',
						'recess.framework.helpers.Url',
						'recess.framework.helpers.Html');
		parent::render($response);
		Layout::extendEnd();
	}
}
?>