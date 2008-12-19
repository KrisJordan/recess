<?php

/** !RoutesPrefix hello/ */
class TestController extends Controller {
	
	/** !Route GET, methods */
	function methodsGet() {
		die('<form method="POST" action=""> ' .
			'<input type="submit" />' .
			'</form>');
	}
	
	/** !Route POST, methods */
	function methodsPost() {
		die("POSTED!");
	}
	
	function world ($name) {
		die("Hello $name!");
	}	
	
	function universe () {
		die('Hello Universe!');
	}
	
	/**
	 * !Route GET, recess
	 * !Route GET, $firstName/$lastName
	 * */
	function genericHello ($firstName = "From", $lastName="Recess") {
		die("Hello $firstName $lastName");
	}
	
}

?>