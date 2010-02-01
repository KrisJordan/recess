<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/lang/RecessLangAllTests.php';

class RecessAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess');

        $suite->addTestSuite(RecessLangAllTests::suite());
 		
        return $suite;
    }
}