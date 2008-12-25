<?php
// Pre-release test runner. This will become a mini-app in Recess Tools

require_once('recess/lib/simpletest/autorun.php');
require_once('recess/lib/simpletest/mock_objects.php');
require_once('recess/lib/simpletest/unit_tester.php');

$_ENV['dir.bootstrap'] = str_replace('\\','/',realpath(dirname(__FILE__))) . '/';
$_ENV['url.base'] = str_replace('TestRunner.php', '', $_SERVER['PHP_SELF']);

$_ENV['dir.recess'] = $_ENV['dir.bootstrap'] . 'recess/';
$_ENV['dir.apps'] = $_ENV['dir.bootstrap'] . 'apps/';
$_ENV['dir.test'] = $_ENV['dir.recess'] . 'test/';
$_ENV['dir.temp'] = $_ENV['dir.recess'] . 'temp/';
$_ENV['dir.lib'] = $_ENV['dir.recess'] . 'lib/';
$_ENV['url.content'] = $_ENV['url.base'] . 'content/';

require_once($_ENV['dir.lib'] . 'recess/lang/Library.class.php');
Library::addClassPath($_ENV['dir.lib']);

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