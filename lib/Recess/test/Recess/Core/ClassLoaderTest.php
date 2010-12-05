<?php
include_once __DIR__ . '/../../../../Recess/Core/ClassLoader.php';
use Recess\Core\ClassLoader;

set_include_path(__DIR__.'/../../../'.PATH_SEPARATOR.get_include_path());

/**
 * @group Recess\Core
 */
class ClassLoaderTest extends PHPUnit_Framework_TestCase {
	
	function setup(){
		ClassLoader::reset();
	}
	
	function testOnLoad() {
		$onLoad = ClassLoader::onLoad();
		$this->assertType('Recess\Core\Event',$onLoad);
		$onLoad2 = ClassLoader::onLoad();
		$this->assertTrue($onLoad === $onLoad2);
		$theClass = false;
		ClassLoader::onLoad()->callback(function($class) use (&$theClass) { $theClass = $class; });
		$this->assertTrue(ClassLoader::load('Recess\Core\Dummy'));
		$this->assertEquals('Recess\Core\Dummy',$theClass);
	}
	
	function testWrapLoad() {
		$loadedCount = 0;
		ClassLoader::wrapLoad(
			function($load,$class) use (&$loadedCount) {
				if($load($class)) {
					$loadedCount += 1;
					return true;
				} else {
					$loadedCount -= 1;
				}
			});
		ClassLoader::load('Recess\Core\Dummy');
		ClassLoader::load('Recess\Core\AnotherDummy');
		$this->assertEquals(2,$loadedCount);
	}
	
	function testLoadDummy() {
		ClassLoader::load('Recess\Core\Dummy');
		$dummy = new Recess\Core\Dummy;
		$this->assertType('Recess\Core\Dummy',$dummy);
		$this->assertEquals('hello world',$dummy->helloWorld());
	}
	
	function testWrapAfterLoadDummy() {
		ClassLoader::load('Recess\Core\Dummy');
		$loadedCount = 0;
		ClassLoader::wrapLoad(
			function($load,$class) use (&$loadedCount) {
				if($load($class)) {
					$loadedCount += 1;
					return true;
				} else {
					$loadedCount -= 1;
				}
			});
		ClassLoader::load('Recess\Core\AnotherDummy');
		$this->assertEquals(1,$loadedCount);
	}
	
	function testLoadNonExistingClass() {
		$this->assertFalse(ClassLoader::load('Recess\Core\DummyDummy'));
	}
	
	function testLoadFileWithoutClass() {
		try {
			ClassLoader::load('Recess\Core\DummyFns');
			$this->fail('ClassLoader should throw after including a file that does not load the requested class.');
		} catch(Exception $e) {
			$this->assertTrue(true);
		}
	}
	
	function testDeprecatedClassNameSupport() {
		$this->assertTrue(ClassLoader::load('Recess\Core\Deprecated_ClassNames'));
	}
	
}
