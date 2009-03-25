<?php
Library::import('recess.framework.controllers.Controller');

/**
 * !View Native, Prefix: home/
 */
class {{programmaticName}}HomeController extends Controller {
	
	/** !Route GET */
	function index() {
		
		$this->flash = 'Welcome to your new Recess application!';
		
	}
	
}
?>