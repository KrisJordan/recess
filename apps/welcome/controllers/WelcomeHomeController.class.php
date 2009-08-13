<?php
Library::import('recess.framework.controllers.Controller');

/**
 * !RespondsWith Layouts, Json
 * !Prefix Routes: /, Views: home/
 */
class WelcomeHomeController extends Controller {
	/**
	 * !Route GET, /
	 */
	function index() {
		$this->flash = 'Welcome to your new Recess app!';
	}
}
?>
