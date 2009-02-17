<?php
require_once 'PHPUnit/Framework.php';

require_once 'recess/RecessAllTests.php';

class AllTests {
	
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('recess');
		
		$suite->addTestSuite(RecessAllTests::suite());
		
		return $suite;
	}
	
}
?>