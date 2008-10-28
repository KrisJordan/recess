<?php

Library::import('recess.http.responses.ErrorResponse');
Library::import('recess.http.ResponseCodes');

class BadRequestResponse extends ErrorResponse {
	public function __construct(Request $request) {
		parent::__construct($request, ResponseCodes::HTTP_BAD_REQUEST, ResponseCodes::getMessageForCode(ResponseCodes::HTTP_BAD_REQUEST));
	}
}

?>