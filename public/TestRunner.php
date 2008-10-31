<?php
// Pre-release test runner. This will become a plug-in.

require_once('../lib/simpletest/autorun.php');
require_once('../lib/simpletest/mock_objects.php');
require_once('../lib/simpletest/unit_tester.php');
require_once('../lib/Recess.php');

class AllTests extends TestSuite {
	function __construct() {
		$this->TestSuite('All Tests');
		$this->addFile(dirname(__FILE__) . '/../test/lib/recess/utility/BoxTest.class.php');
		$this->addFile(dirname(__FILE__) . '/../test/lib/recess/framework/conventions/default/routing/RoutingNodeTest.class.php');
		$this->addFile(dirname(__FILE__) . '/../test/lib/recess/sources/db/sql/SelectSqlBuilderTest.class.php');
		$this->addFile(dirname(__FILE__) . '/../test/lib/recess/sources/db/SelectedSetTest.class.php');
		$this->addFile(dirname(__FILE__) . '/../test/lib/recess/sources/db/pdo/SqlitePdoDataSourceTest.class.php');
		$this->addFile(dirname(__FILE__) . '/../test/lib/recess/lang/RecessReflectionClassTest.class.php');
	}
}

?>