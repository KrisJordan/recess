<?php
// Pre-release test runner. This will become a plug-in.

require_once('recess/lib/simpletest/autorun.php');
require_once('recess/lib/simpletest/mock_objects.php');
require_once('recess/lib/simpletest/unit_tester.php');
require_once('recess/lib/recess/Recess.php');

class AllTests extends TestSuite {
	function __construct() {
		$this->TestSuite('All Tests');
		$this->addFile(dirname(__FILE__) . '/recess/test/lib/recess/lang/InflectorTest.class.php');
		$this->addFile(dirname(__FILE__) . '/recess/test/lib/recess/lang/RecessObjectTest.class.php');
		$this->addFile(dirname(__FILE__) . '/recess/test/lib/recess/framework/routing/RtNodeTest.class.php');
		$this->addFile(dirname(__FILE__) . '/recess/test/lib/recess/database/sql/SelectSqlBuilderTest.class.php');
		$this->addFile(dirname(__FILE__) . '/recess/test/lib/recess/database/pdo/PdoDataSetTest.class.php');
		$this->addFile(dirname(__FILE__) . '/recess/test/lib/recess/database/pdo/SqlitePdoDataSourceTest.class.php');
		$this->addFile(dirname(__FILE__) . '/recess/test/lib/recess/lang/RecessReflectionClassTest.class.php');
		$this->addFile(dirname(__FILE__) . '/recess/test/lib/recess/database/orm/ModelTest.class.php');
	}
}

?>