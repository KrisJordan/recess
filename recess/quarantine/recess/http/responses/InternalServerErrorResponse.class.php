<?php

Library::import('recess.http.responses.ErrorResponse');
Library::import('recess.http.ResponseCodes');

class InternalServerErrorResponse extends ErrorResponse {
	public function __construct(Request $request) {
		parent::__construct($request, ResponseCodes::HTTP_INTERNAL_SERVER_ERROR, ResponseCodes::getMessageForCode(ResponseCodes::HTTP_INTERNAL_SERVER_ERROR));
	}
}

?>