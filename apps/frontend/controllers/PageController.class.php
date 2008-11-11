<?php

class PageController extends Controller {
	
	/** !Route GET, /pages/view */ 
	function viewPage() {
		echo 'hell yea boys.';exit;
	}
	
	/** !Route GET, /omg */
	function awesome() {
		echo 'this is awesome'; exit;
	}
	
}

?>