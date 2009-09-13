<?php
require_once 'PHPUnit/Framework.php';

include_once __DIR__ . '/../../../recess/lang/ClassLoader.class.php';
use recess\lang\ClassLoader;

set_include_path(__DIR__.'/../../'.PATH_SEPARATOR.get_include_path());

class ClassLoaderTest extends PHPUnit_Framework_TestCase {
	
	function testOnLoad() {
		$onLoad = ClassLoader::onLoad();
		$this->assertType('recess\lang\Event',$onLoad);
		$onLoad2 = ClassLoader::onLoad();
		$this->assertTrue($onLoad === $onLoad2);
		$theClass = '';
		ClassLoader::onLoad()->call(function($class) use (&$theClass) { $theClass = $class; });
		ClassLoader::load('recess\lang\Dummy');
		$this->assertEquals('recess\lang\Dummy',$theClass);
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
		ClassLoader::load('recess\lang\Dummy');
		$dummy = new recess\lang\Dummy;
		$this->assertType('recess\lang\Dummy',$dummy);
		$this->assertEquals('hello world',$dummy->helloWorld());
	}
	
	function testWrapAfterLoadDummy() {
		ClassLoader::load('recess\lang\Dummy');
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