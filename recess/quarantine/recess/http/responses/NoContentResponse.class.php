<?php

Library::import('recess.http.Response');
Library::import('recess.http.ResponseCodes');

class NoContentResponse extends Response {
	public function __construct(Request $request) {
		parent::__construct($request, ResponseCodes::HTTP_NO_CONTENT, ResponseCodes::getMessageForCode(ResponseCodes::HTTP_NO_CONTENT));
	}
}

?>