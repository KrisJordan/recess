<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/lang/RecessObjectTest.php';
require_once 'recess/lang/RecessReflectionClassTest.php';
require_once 'recess/lang/InflectorTest.php';

class RecessLangAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess.lang');

        $suite->addTestSuite('RecessObjectTest');
 		$suite->addTestSuite('RecessReflectionClassTest');
 		$suite->addTestSuite('InflectorTest');
 		
        return $suite;
    }
}
?>