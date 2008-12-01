<?php
Library::import('recess.http.ForwardingResponse');
Library::import('recess.http.ResponseCodes');

class ForwardingNotFoundResponse extends ForwardingResponse {
	public $context;
	
	public function __construct(Request $request, $contentUri, $context = '') {
		parent::__construct($request, ResponseCodes::HTTP_NOT_FOUND, $contentUri);
		$this->context = $context;
	}
}
?>