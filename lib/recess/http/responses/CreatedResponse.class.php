<?php
Library::import('recess.http.ForwardingResponse');
Library::import('recess.http.ResponseCodes');

class CreatedResponse extends ForwardingResponse {

	public function __construct(Request $request, $resourceUri, $contentUri = null) {
		if(!isset($contentUri)) $contentUri = $resourceUri;
		parent::__construct($request, ResponseCodes::HTTP_CREATED, $contentUri);
		$this->addHeader('Location: http://localhost' . $resourceUri); // TODO: This should not reference localhost!
	}
	
}
?>