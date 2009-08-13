<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/framework/helpers/blocks/HtmlBlockTest.php';
require_once 'recess/framework/helpers/blocks/ListBlockTest.php';
require_once 'recess/framework/helpers/blocks/PartBlockTest.php';

class RecessFrameworkHelpersBlocksAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess.framework.helpers.blocks');

        $suite->addTestSuite('HtmlBlockTest');
        $suite->addTestSuite('ListBlockTest');
        $suite->addTestSuite('PartBlockTest');
 		
        return $suite;
    }
}
?>