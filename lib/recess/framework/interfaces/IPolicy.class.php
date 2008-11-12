<?php

interface IPolicy {
	public function preprocess(Request &$request);
	
	public function getControllerFor(Request &$request, array $applications, RoutingNode $routes);
	
	public function getViewFor(Response &$response);
}

?>