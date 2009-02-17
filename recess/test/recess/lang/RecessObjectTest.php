<?php
require_once 'PHPUnit/Framework.php';

Library::import('recess.lang.RecessObject');

class MyRecessObject extends RecessObject {}

class MyNewClassMethodProvider {
	function callMe() {
		return 'Hello World!';
	}
}

class RecessObjectTest extends PHPUnit_Framework_TestCase
{ 
	function testAttachedMethod() {
		$myRecessObject = new MyRecessObject();
		try {
			$this->assertEquals(
				'Hello World!',
				$myRecessObject->helloWorld()
				);
			$this->hasFailed();
		} catch (RecessException $e) {
			// Success
		}
		
		$attachedMethodProvider = new MyNewClassMethodProvider();
		MyRecessObject::attachMethod('MyRecessObject','helloWorld',$attachedMethodProvider,'callMe');
		
		try {
			$this->assertEquals(
				$myRecessObject->helloWorld(),
				'Hello World!');
			// Success
		} catch (RecessException $e) {
			$this->hasFailed();
		}
	}
	
	function testPropertiesPattern() {
		$RecessObject = new MyRecessObject();

		$this->assertFalse(isset($RecessObject->prop));
		
		$RecessObject->prop = true;
		$this->assertTrue(isset($RecessObject->prop));
		$this->assertTrue($RecessObject->prop);
		
		unset($RecessObject->prop);
		$this->assertFalse(isset($RecessObject->prop));
		
		$settings = array('key1' => 'value1', 'key2' => 'value2');
		foreach($settings as $key => $value) {
			$RecessObject->$key = $value;
		}
		
		foreach($RecessObject as $key => $value) {
			$this->assertEquals($settings[$key], $RecessObject->$key);
		}
 	}
}
?>