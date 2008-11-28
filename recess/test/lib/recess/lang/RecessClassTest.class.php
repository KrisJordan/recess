<?php

Library::import('recess.lang.RecessClass');

class MyRecessClass extends RecessClass {}

class MyNewClassMethodProvider {
	function callMe() {
		return 'Hello World!';
	}
}

class RecessClassTest extends UnitTestCase {
	
	function testAttachedMethod() {
		$myRecessObject = new MyRecessClass();
		try {
			$this->assertEqual(
				$myRecessObject->helloWorld(),
				'Hello World!');
			$this->fail('Should throw method undefined exception.');
		} catch (RecessException $e) {
			$this->pass('Successfully threw method undefined exception.');
		}
		
		$attachedMethodProvider = new MyNewClassMethodProvider();
		MyRecessClass::attachMethod('MyRecessClass','helloWorld',$attachedMethodProvider,'callMe');
		
		try {
			$this->assertEqual(
				$myRecessObject->helloWorld(),
				'Hello World!');
			$this->pass('Successfully did not throw method undefined exception.');
		} catch (RecessException $e) {
			$this->fail('Should not threw method undefined exception.');
		}
	}
	
	function testPropertiesPattern() {
		$recessClass = new MyRecessClass();
		
		$this->assertFalse(isset($recessClass->prop));
		
		$recessClass->prop = true;
		$this->assertTrue(isset($recessClass->prop));
		$this->assertTrue($recessClass->prop);
		
		unset($recessClass->prop);
		$this->assertFalse(isset($recessClass->prop));
		
		$settings = array('key1' => 'value1', 'key2' => 'value2');
		foreach($settings as $key => $value) {
			$recessClass->$key = $value;
		}
		
		foreach($recessClass as $key => $value) {
			$this->assertEqual($recessClass->$key, $settings[$key]);
		}
	}
	
}

?>