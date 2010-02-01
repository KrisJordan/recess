<?php
Library::import('recess.lang.Inflector');

class InflectorTest extends PHPUnit_Framework_TestCase {
	
	function testToPlural() {
		$this->assertEquals('things', Inflector::toPlural('thing'));
		$this->assertEquals('persons', Inflector::toPlural('person'));
		$this->assertEquals('oxs', Inflector::toPlural('ox'));
	}
	
	function testToSingular() {
		$this->assertEquals('thing', Inflector::toSingular('things'));
		$this->assertEquals('person', Inflector::toSingular('persons'));
		$this->assertEquals('ox', Inflector::toSingular('oxs'));
	}
	
	function testIsPlural() {
		$this->assertTrue(Inflector::isPlural('things'));
		$this->assertTrue(Inflector::isPlural('persons'));
		$this->assertTrue(Inflector::isPlural('oxs'));
		$this->assertFalse(Inflector::isPlural('ox'));
		$this->assertFalse(Inflector::isPlural('person'));
	}
	
	function testToProperCaps() {
		$this->assertEquals('ProperCaps', Inflector::toProperCaps('properCaps'));
		$this->assertEquals('ProperCaps', Inflector::toProperCaps('proper_caps'));
	}
	
	function testToCamelCaps() {
		$this->assertEquals('camelCaps', Inflector::toCamelCaps('CamelCaps'));
		$this->assertEquals('camelCaps', Inflector::toCamelCaps('camel_caps'));
	}
	
	function testToUnderscores() {
		$this->assertEquals('under_scores', Inflector::toUnderscores('UnderScores'));
		$this->assertEquals('under_scores', Inflector::toUnderscores('underScores'));
	}
	
	function testToEnglish() {
		$this->assertEquals('To English', Inflector::toEnglish('toEnglish'));
		$this->assertEquals('To English', Inflector::toEnglish('ToEnglish'));
		$this->assertEquals('To English', Inflector::toEnglish('to_english'));
		$this->assertEquals('To English', Inflector::toEnglish('_____to______english____'));
	}
	
}

?>