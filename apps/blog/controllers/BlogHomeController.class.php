<?php
Library::import('recess.framework.controllers.Controller');

/**
 * !View Native, Prefix: home/
 */
class BlogHomeController extends Controller {
	
	/** !Route GET */
	function index() {
		
		$this->flash = 'Welcome to your application!';
		
	}

	/** !Route GET, mysql */
	function someOtherFn() {
			
		Library::import('blog.models.AllType');
		
		$allTypes = new AllType();
		$allTypes->aBlob = "BLOB!";
		$allTypes->aBoolean = false;
		$allTypes->aDate = time();
		$allTypes->aTime = time();
		$allTypes->aDateTime = time();
		$allTypes->aFloat = 2.85;
		$allTypes->anInteger = 1200;
		$allTypes->aString = 'Hello world';
		$allTypes->aText = 'Herro world';
		$allTypes->insert();
		
		
		$allTypes = new AllType();
		foreach($allTypes->all() as $type) {
			print_r($type);
			echo $type->aBoolean === true ? 'true' : 'false';
			echo '<br />';
		}
		exit;
	}
	
		
}
?>