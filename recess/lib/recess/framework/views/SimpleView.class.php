<?php

Library::import('recess.views.AbstractView');
Library::import('recess.http.Response');

class SimpleView extends AbstractView {
	public function render(Response $response) {
		if($response->code == ResponseCodes::HTTP_OK) {
			switch($response->request->format) {
				case Formats::JSON:
					print JSON_encode($response->data);
					break;
				default:
					print_r($response);
			}
		}
	}
}

?>