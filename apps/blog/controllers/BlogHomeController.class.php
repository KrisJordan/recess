<?php
Library::import('recess.framework.controllers.Controller');

/**
 * !View Native, Prefix: home/
 */
class BlogHomeController extends Controller {
	
	/** !Route GET */
	function home() {
		
		$this->flash = 'Welcome to your application!';
		
	}
	
}
?>