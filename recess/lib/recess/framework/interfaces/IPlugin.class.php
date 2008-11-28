<?php

interface IPlugin {
	
	function refine(Request $request);
	
	function serve(Request $request);
	
	function respondWith(Response $response);
	
}

?>