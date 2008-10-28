<?php

Library::import('recess.View');
Library::import('recess.http.Response');

class SimpleView extends View {
	public function render(Response $response) {
		if($response->code == ResponseCodes::HTTP_OK) {
			switch($response->request->format) {
				case Formats::json:
					print json_encode($response->data);
					break;
				default:
					print_r($response);
			}
		}
	}
}

?>