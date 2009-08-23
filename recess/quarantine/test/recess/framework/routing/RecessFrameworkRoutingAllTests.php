<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/framework/routing/RtNodeTest.php';

class RecessFrameworkRoutingAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess.framework.routing');

        $suite->addTestSuite('RtNodeTest');
 		
        return $suite;
    }
}
?>