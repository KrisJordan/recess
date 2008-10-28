<?php
// Pre-release test runner. This will become a plug-in.

require_once('../lib/simpletest/autorun.php');
require_once('../lib/Recess.php');

class AllTests extends TestSuite {
	function __construct() {
		$this->TestSuite('All Tests');
		$this->addFile(dirname(__FILE__) . '/../test/lib/recess/BoxTest.class.php');
	}
}

?>