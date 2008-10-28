<?php

Library::import('recess.http.responses.ErrorResponse');
Library::import('recess.http.ResponseCodes');

class ConflictResponse extends ErrorResponse {
	public function __construct(Request $request) {
		parent::__construct($request, ResponseCodes::HTTP_CONFLICT, ResponseCodes::getMessageForCode(ResponseCodes::HTTP_CONFLICT));
	}
}

?>