<?php
require_once 'PHPUnit/Framework.php';

require_once 'recess/lang/RecessLangAllTests.php';
require_once 'recess/framework/RecessFrameworkAllTests.php';
require_once 'recess/database/RecessDatabaseAllTests.php';
require_once 'recess/http/RecessHttpAllTests.php';

class RecessAllTests {
	
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('recess');
		
		$suite->addTestSuite(RecessLangAllTests::suite());
		$suite->addTestSuite(RecessFrameworkAllTests::suite());
		$suite->addTestSuite(RecessDatabaseAllTests::suite());
		
		return $suite;
	}
	
}
?>