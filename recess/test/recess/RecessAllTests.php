<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/core/RecessCoreAllTests.php';
require_once 'recess/lang/RecessLangAllTests.php';

class RecessAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess\lang');

        $suite->addTestSuite(RecessCoreAllTests::suite());
        $suite->addTestSuite(RecessLangAllTests::suite());
 		
        return $suite;
    }
}