<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/http/AcceptsTest.php';
require_once 'recess/http/AcceptsListTest.php';

class RecessHttpAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess.http');

        $suite->addTestSuite('AcceptsTest');
        $suite->addTestSuite('AcceptsListTest');
 		
        return $suite;
    }
}
?>