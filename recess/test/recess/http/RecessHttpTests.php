<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/http/ContentNegotiationTest.php';

class RecessHttpAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess.http');

        $suite->addTestSuite('ContentNegotiationTest');
 		
        return $suite;
    }
}
?>