<?php

Library::import('recess.lang.RecessObject');

class MyRecessObject extends RecessObject {}

class MyNewClassMethodProvider {
	function callMe() {
		return 'Hello World!';
	}
}

class RecessObjectTest extends UnitTestCase {
	
	function testAttachedMethod() {
		$myRecessObject = new MyRecessObject();
		try {
			$this->assertEqual(
				$myRecessObject->helloWorld(),
				'Hello World!');
			$this->fail('Should throw method undefined exception.');
		} catch (RecessException $e) {
			$this->pass('Successfully threw method undefined exception.');
		}
		
		$attachedMethodProvider = new MyNewClassMethodProvider();
		MyRecessObject::attachMethod('MyRecessObject','helloWorld',$attachedMethodProvider,'callMe');
		
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
			$this->assertEqual($RecessObject->$key, $settings[$key]);
		}
	}
	
}

?>