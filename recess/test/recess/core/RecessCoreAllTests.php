<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/core/CandyTest.php';
require_once 'recess/core/EventTest.php';

class RecessCoreAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess\core');

        $suite->addTestSuite('EventTest');
 		$suite->addTestSuite('CandyTest');
 		
        return $suite;
    }
}