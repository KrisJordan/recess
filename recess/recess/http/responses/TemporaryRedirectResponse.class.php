<?php
Library::import('recess.http.Response');
Library::import('recess.http.ResponseCodes');

class TemporaryRedirectResponse extends Response {
	public function __construct(Request $request, $resourceUri, $data = array()) {
		parent::__construct($request, ResponseCodes::HTTP_TEMPORARY_REDIRECT, $data);
		$this->addHeader("Location: $resourceUri");
	}
}
?>