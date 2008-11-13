<?php
Library::import('recess.http.ForwardingResponse');
Library::import('recess.http.ResponseCodes');

class ForwardingOkResponse extends ForwardingResponse {
	public function __construct(Request $request, $contentUri) {
		parent::__construct($request, ResponseCodes::HTTP_OK, $contentUri);
	}
}
?>