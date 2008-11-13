<?php
Library::import('recess.http.responses.ErrorResponse');
Library::import('recess.http.ResponseCodes');

class ForbiddenResponse extends ErrorResponse {
	public function __construct(Request $request) {
		parent::__construct($request, ResponseCodes::HTTP_FORBIDDEN, ResponseCodes::getMessageForCode(ResponseCodes::HTTP_FORBIDDEN));
	}
}

?>