<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/framework/helpers/AssertiveTemplateTest.php';
require_once 'recess/framework/helpers/BufferTest.php';
require_once 'recess/framework/helpers/PartTest.php';
require_once 'recess/framework/helpers/LayoutTest.php';
require_once 'recess/framework/helpers/blocks/RecessFrameworkHelpersBlocksAllTests.php';

class RecessFrameworkHelpersAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess.framework.helpers');

        $suite->addTestSuite('BufferTest');
        $suite->addTestSuite('AssertiveTemplateTest');
        $suite->addTestSuite('PartTest');
        $suite->addTestSuite('LayoutTest');
        $suite->addTestSuite(RecessFrameworkHelpersBlocksAllTests::suite());
 		
        return $suite;
    }
}
?>