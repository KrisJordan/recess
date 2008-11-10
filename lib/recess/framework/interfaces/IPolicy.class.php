<?php

interface IPolicy {
	public function getPreprocessor();
	
	public function getControllerFor(Request $request);
	
	public function getViewFor(Response $response);
}

?>