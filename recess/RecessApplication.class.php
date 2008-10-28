<?php

Library::import('recess.http.Request');
Library::import('recess.policies.StandardPolicy');

abstract class RecessApplication {
		
	public static function getPolicy() { return null; }
	
}

?>