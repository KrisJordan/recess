<?php
Library::import('recess.http.Response');

abstract class ForwardingResponse extends Response {
	
	public $forwardUri;
	
	public function __construct(Request $request, $code, $forwardUri) {
		parent::__construct($request, $code);
		$this->forwardUri = $forwardUri;
	}
	
}
?>