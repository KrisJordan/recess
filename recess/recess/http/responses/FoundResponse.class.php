<?php
Library::import('recess.http.Response');
Library::import('recess.http.ResponseCodes');

class FoundResponse extends Response {
	public function __construct(Request $request, $resourceUri, $data = array()) {
		parent::__construct($request, ResponseCodes::HTTP_FOUND, $data);
		$this->addHeader("Location: $resourceUri");
	}
}
?>