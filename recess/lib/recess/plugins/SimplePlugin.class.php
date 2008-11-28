<?php

Library::import('recess.interfaces.IPlugin');

class SimplePlugin implements IPlugin {
	function refine(Request $request) {
		return $request;
	}
	
	function serve(Request $request) {
		return $request;
	}
	
	function respondWith(Response $response) {
		return null;
	}
}

?>