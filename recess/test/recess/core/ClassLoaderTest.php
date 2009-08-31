<?php
require_once 'PHPUnit/Framework.php';

include_once __DIR__ . '/../../../recess/core/ClassLoader.class.php';
use recess\core\ClassLoader;

class ClassLoaderTest extends PHPUnit_Framework_TestCase {
	
	function testOnLoad() {
		$onLoad = ClassLoader::onLoad();
		$this->assertType('recess\core\Event',$onLoad);
		$onLoad2 = ClassLoader::onLoad();
		$this->assertTrue($onLoad === $onLoad2);
		$theClass = '';
		ClassLoader::onLoad()->call(function($class) use (&$theClass) { $theClass = $class; });
		ClassLoader::load('recess\core\Dummy');
		$this->assertEquals('recess\core\Dummy',$theClass);
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
		ClassLoader::load('ClassLoaderTest');
		ClassLoader::load('ClassLoaderTest');
		$this->assertEquals(2,$loadedCount);
	}
	
	function testLoadDummy() {
		set_include_path(__DIR__.'/../../');
		ClassLoader::load('recess\core\Dummy');
		$dummy = new recess\core\Dummy;
		$this->assertType('recess\core\Dummy',$dummy);
		$this->assertEquals('hello world',$dummy->helloWorld());
	}
	
	function testWrapAfterLoadDummy() {
		set_include_path(__DIR__.'/../../');
		ClassLoader::load('recess\core\Dummy');
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
		ClassLoader::load('ClassLoaderTest');
		ClassLoader::load('ClassLoaderTest');
		$this->assertEquals(2,$loadedCount);
	}
	
}