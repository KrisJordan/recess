<?php
Library::import('recess.http.ForwardingResponse');
Library::import('recess.http.ResponseCodes');

class ForwardingUnauthorizedResponse extends ForwardingResponse {
	public function __construct(Request $request, $contentUri, $realm = '') {
		parent::__construct($request, ResponseCodes::HTTP_UNAUTHORIZED, $contentUri);
		if($realm != '')
			$this->addHeader('WWW-Authenticate: Basic realm="' . $realm . '"');
	}
}
?>