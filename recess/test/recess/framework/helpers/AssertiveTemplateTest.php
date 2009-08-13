<?php
Library::import('recess.framework.helpers.AssertiveTemplate');
Library::import('recess.framework.helpers.exceptions.InputTypeCheckException');
Library::import('recess.framework.helpers.exceptions.InputDoesNotExistException');
Library::import('recess.framework.helpers.exceptions.MissingRequiredDrawArgumentException');
Library::import('recess.framework.helpers.exceptions.MissingRequiredInputException');
class AssertiveTemplateTest extends PHPUnit_Framework_TestCase {
	protected $simple = '';
	
	function setUp() {
		$this->simple = 'simple.at.php';
		AssertiveTemplate::addPath(dirname(__FILE__) . '/');
	}
	
	function testGetInputs() {
		$inputs = AssertiveTemplate::getInputs($this->simple);
		$this->assertEquals(3, count($inputs));
		
		$this->assertEquals(array('title','aBlock','max'), array_keys($inputs));
		
		$this->assertEquals("string", $inputs['title']['type']);
		$this->assertTrue($inputs['title']['required']);
		
		$this->assertEquals("Block", $inputs['aBlock']['type']);
		$this->assertTrue($inputs['aBlock']['required']);
		
		$this->assertEquals("int", $inputs['max']['type']);
		$this->assertFalse($inputs['max']['required']);
	}
	
	function testRequiredInputs() {
		$string = '1234';
		$object = new stdclass;
		$int = 1234;
		$float = 12.24;
		$bool = true;
		
		try{
			AssertiveTemplate::input($string, 'string');
			AssertiveTemplate::input($object, 'stdclass');
			AssertiveTemplate::input($int, 'int');
			AssertiveTemplate::input($float, 'float');
			AssertiveTemplate::input($bool, 'bool');
		} catch(Exception $e) {
			$this->assertTrue(false);
			return;
		}
		$this->assertTrue(true);
	}
	
	function testOptionalInputs() {
		AssertiveTemplate::input($string, 'string', '1234');
		$stdclass = new stdclass;
		AssertiveTemplate::input($object, 'stdclass', $stdclass);
		AssertiveTemplate::input($int, 'int', 1234);
		AssertiveTemplate::input($float, 'float', 12.24);
		AssertiveTemplate::input($bool, 'bool', true);
		
		$this->assertEquals('1234', $string);
		$this->assertEquals($stdclass, $object);
		$this->assertEquals(1234, $int);
		$this->assertEquals(12.24, $float);
		$this->assertTrue($bool);
	}
	
	function testInputFailure() {
		$string = '1234';
		$object = new stdclass;
		$int = 1234;
		$float = 12.24;
		$bool = true;
		try {	AssertiveTemplate::input($object, 'string'); $this->assertTrue(false); } catch(Exception $e) { $this->assertTrue(true); }
		try {	AssertiveTemplate::input($int, 'stdclass');	$this->assertTrue(false); } catch(Exception $e) { $this->assertTrue(true); }
		try {	AssertiveTemplate::input($float, 'int'); $this->assertTrue(false); } catch(Exception $e) { $this->assertTrue(true); }
		try {	AssertiveTemplate::input($bool, 'float'); $this->assertTrue(false); } catch(Exception $e) { $this->assertTrue(true); }
		try {	AssertiveTemplate::input($string, 'bool'); $this->assertTrue(false); } catch(Exception $e) { $this->assertTrue(true); }
	}
}

?>