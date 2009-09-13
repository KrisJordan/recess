<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/lang/CandyTest.php';
require_once 'recess/lang/EventTest.php';
require_once 'recess/lang/ClassLoaderTest.php';
require_once 'recess/lang/AnnotationTest.php';
require_once 'recess/lang/AttachedMethodTest.php';
require_once 'recess/lang/ReflectionMethodTest.php';
require_once 'recess/lang/ReflectionClassTest.php';
require_once 'recess/lang/ObjectTest.php';

class RecessLangAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess\lang');

        $suite->addTestSuite('EventTest');
        $suite->addTestSuite('CandyTest');
        $suite->addTestSuite('ClassLoaderTest');
        
        $suite->addTestSuite('AnnotationTest');
        $suite->addTestSuite('AttachedMethodTest');
        $suite->addTestSuite('ReflectionClassTest');
        $suite->addTestSuite('ReflectionMethodTest');
 		$suite->addTestSuite('ObjectTest');
 		
        return $suite;
    }
}