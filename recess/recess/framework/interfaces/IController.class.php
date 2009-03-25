<?php
Library::import('recess.http.Request');

interface IController {
	// function wrappedServe(Request $request);
	static function getRoutes($class);
}

?>