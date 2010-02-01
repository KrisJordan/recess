<?php

Library::import('recess.http.Response');
Library::import('recess.http.ResponseCodes');

class OkResponse extends Response {
	public function __construct(Request $request, $data = array()) {
		parent::__construct($request, ResponseCodes::HTTP_OK, $data);
	}
}

?>