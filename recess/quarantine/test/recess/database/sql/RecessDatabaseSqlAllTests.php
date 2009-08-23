<?php
require_once 'PHPUnit/Framework.php';
require_once 'recess/database/sql/SqlBuilderTest.php';
require_once 'recess/database/sql/SelectSqlBuilderTest.php';

class RecessDatabaseSqlAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('recess.database.sql');

        $suite->addTestSuite('SqlBuilderTest');
 		$suite->addTestSuite('SelectSqlBuilderTest');
 		
        return $suite;
    }
}
?>