<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/lang/AnnotationTest.php';
require_once 'recess/lang/AttachedMethodTest.php';
require_once 'recess/lang/ReflectionMethodTest.php';
require_once 'recess/lang/ObjectTest.php';

class RecessLangAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess\lang');

        $suite->addTestSuite('AnnotationTest');
        $suite->addTestSuite('AttachedMethodTest');
        $suite->addTestSuite('ReflectionMethodTest');
 		$suite->addTestSuite('ObjectTest');
 		
        return $suite;
    }
}