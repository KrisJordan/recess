<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/framework/routing/RecessFrameworkRoutingAllTests.php';
require_once 'recess/framework/helpers/RecessFrameworkHelpersAllTests.php';

class RecessFrameworkAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess.framework');

        $suite->addTestSuite(RecessFrameworkRoutingAllTests::suite());
        $suite->addTestSuite(RecessFrameworkHelpersAllTests::suite());
 		
        return $suite;
    }
}
?>