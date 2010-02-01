<?php
Library::import('recess.http.ForwardingResponse');
Library::import('recess.http.ResponseCodes');

class ForwardingForbiddenResponse extends ForwardingResponse {
	public $context;
	
	public function __construct(Request $request, $contentUri, $context = '') {
		parent::__construct($request, ResponseCodes::HTTP_FORBIDDEN, $contentUri);
		$this->context = $context;
	}
}
?>