<?php

Library::import('recess.http.responses.ErrorResponse');
Library::import('recess.http.ResponseCodes');

class NotImplementedResponse extends ErrorResponse {
	public function __construct(Request $request) {
		parent::__construct($request, ResponseCodes::HTTP_NOT_IMPLEMENTED, ResponseCodes::getMessageForCode(ResponseCodes::HTTP_NOT_IMPLEMENTED));
	}
}

?>