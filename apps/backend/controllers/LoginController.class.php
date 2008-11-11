<?php

class LoginController extends Controller {
	
	/** !Route GET, login/$name */
	function showLogin($name) {
		echo 'login welcomes ' . $name; exit;
	}
	
	function kris($lastname) {
		echo 'kris' . $lastname; exit;
	}
	
}

?>