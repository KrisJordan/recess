<?php
require_once 'PHPUnit/Framework.php';

Library::import('recess.lang.Object');

class MyObject extends Object {}

class MyNewClassMethodProvider {
	function callMe() {
		return 'Hello World!';
	}
}

class ObjectTest extends PHPUnit_Framework_TestCase
{ 
	function testAttachedMethod() {
		$myObject = new MyObject();
		try {
			$this->assertEquals(
				'Hello World!',
				$myObject->helloWorld()
				);
			$this->hasFailed();
		} catch (RecessException $e) {
			// Success
		}
		
		$attachedMethodProvider = new MyNewClassMethodProvider();
		MyObject::attachMethod('MyObject','helloWorld',$attachedMethodProvider,'callMe');
		
		try {
			$this->assertEquals(
				$myObject->helloWorld(),
				'Hello World!');
			// Success
		} catch (RecessException $e) {
			$this->hasFailed();
		}
	}
	
	function testPropertiesPattern() {
		$Object = new MyObject();

		$this->assertFalse(isset($Object->prop));
		
		$Object->prop = true;
		$this->assertTrue(isset($Object->prop));
		$this->assertTrue($Object->prop);
		
		unset($Object->prop);
		$this->assertFalse(isset($Object->prop));
		
		$settings = array('key1' => 'value1', 'key2' => 'value2');
		foreach($settings as $key => $value) {
			$Object->$key = $value;
		}
		
		foreach($Object as $key => $value) {
			$this->assertEquals($settings[$key], $Object->$key);
		}
 	}
}
?>