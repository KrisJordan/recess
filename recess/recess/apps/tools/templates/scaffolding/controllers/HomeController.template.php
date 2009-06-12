<?php
Library::import('recess.framework.controllers.Controller');

/**
 * !Prefix Views: home/, Routes: /
 */
class {{programmaticName}}HomeController extends Controller {
	
	/** !Route GET */
	function index() {
		
		$this->flash = 'Welcome to your new Recess application!';
		
	}
	
}
?>