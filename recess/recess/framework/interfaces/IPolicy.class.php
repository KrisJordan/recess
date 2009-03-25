<?php

interface IPolicy {
	public function preprocess(Request &$request);
	
	public function getControllerFor(Request &$request, array $applications, RtNode $routes);
	
	public function getViewFor(Response &$response);
}

?>