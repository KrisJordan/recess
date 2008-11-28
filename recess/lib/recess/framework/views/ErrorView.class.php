<?php

Library::import('recess.framework.views.SmartyView');

class ErrorView extends SmartyView {

	protected function templateLocationFor(Response $response) {
		// Ex: errors/404_Not_found.tpl
		return Application::getSetting('Smarty.template_dir') . 
				'errors/' . str_replace(' ', '_', ResponseCodes::getMessageForCode($response->code)) . '.tpl';
	}

}

?>