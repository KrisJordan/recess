<?php

Library::import('recess.http.Response');
Library::import('recess.http.ResponseCodes');

class CreatedResponse extends Response {
	public function __construct(Request $request, $resource_uri) {
		parent::__construct($request, ResponseCodes::HTTP_CREATED, ResponseCodes::getMessageForCode(ResponseCodes::HTTP_CREATED));
		$this->addHeader('Location: http://localhost' . $resource_uri);
	}
}

?>