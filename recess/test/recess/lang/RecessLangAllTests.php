<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/lang/ObjectTest.php';
require_once 'recess/lang/ObjectTest.php';
require_once 'recess/lang/reflection/RecessReflectionClassTest.php';
require_once 'recess/lang/InflectorTest.php';

class RecessLangAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess.lang');

        $suite->addTestSuite('ObjectTest');
 		$suite->addTestSuite('ObjectTest');
 		$suite->addTestSuite('RecessReflectionClassTest');
 		$suite->addTestSuite('InflectorTest');
 		
        return $suite;
    }
}
?>