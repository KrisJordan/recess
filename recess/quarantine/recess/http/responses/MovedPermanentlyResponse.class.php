<?php
Library::import('recess.http.Response');
Library::import('recess.http.ResponseCodes');

class MovedPermanentlyResponse extends Response {
	public function __construct(Request $request, $resourceUri, $data = array()) {
		parent::__construct($request, ResponseCodes::HTTP_MOVED_PERMANENTLY, $data);
		$this->addHeader("Location: $resourceUri");
	}
}
?>