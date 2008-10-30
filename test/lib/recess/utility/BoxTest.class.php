<?php

Library::import('recess.utility.Box');

/**
 * Unit Tests for recess/Box.class.php
 * @author Kris Jordan
 * @see lib/recess/framework/utility/Box.class.php
 */
class BoxTest extends UnitTestCase  {
	protected $box;
	
	function setUp() {
		$this->box = new Box();
	}
	
	function testAssignment() {
		$string = 'Recess!';
		$this->box->name = $string;
		$this->assertEqual($this->box->name, $string);
	}
	
	function testIsSet() { 
		$this->assertFalse(isset($this->box->name));
		$this->box->name = 'Recess!';
		$this->assertTrue(isset($this->box->name));
	}
	
	function testUnset() {
		$this->box->name = 'Recess!';
		$this->assertTrue(isset($this->box->name));
		unset($this->box->name);
		$this->assertFalse(isset($this->box->name));
	}
	
	function testForEach() {
		$array = array('first' => 'second', 'third' => 'fourth');
		foreach($array as $key => $value) {
			$this->box->$key = $value;
		}
		
		$comparison_array = array();
		foreach($this->box as $key => $value) {
			$comparison_array[$key] = $value;
		}
		
		$this->assertEqual($array, $comparison_array);
	}
	
	function tearDown() {
		unset($this->box);
	}
}

?>