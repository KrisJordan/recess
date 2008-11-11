<?php

interface IPolicy {
	public function preprocess(Request $request);
	
	public function getControllerFor(Request $request);
	
	public function getViewFor(Response $response);
}

?>