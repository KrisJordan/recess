<?php

Library::import('recess.http.responses.ErrorResponse');
Library::import('recess.http.ResponseCodes');

class MethodNotAllowedResponse extends ErrorResponse {
	public function __construct(Request $request, $methodsAllowed) {
		parent::__construct($request, ResponseCodes::HTTP_METHOD_NOT_ALLOWED, ResponseCodes::getMessageForCode(ResponseCodes::HTTP_METHOD_NOT_ALLOWED));
		$this->addHeader('Allow: ' . implode(', ', $methodsAllowed));
	}
}

?>