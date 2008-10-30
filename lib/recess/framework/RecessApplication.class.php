<?php
Library::import('recess.http.Request');
Library::import('recess.policies.StandardPolicy');

// @todo Determine Application structuring.

abstract class RecessApplication {
		
	public static function getPolicy() { return null; }
	
}

?>