<?php

Library::import('recess.lang.Inflector');

class InflectorTest extends UnitTestCase {
	
	function testToPlural() {
		$this->assertEqual('things', Inflector::toPlural('thing'));
		$this->assertEqual('persons', Inflector::toPlural('person'));
		$this->assertEqual('oxs', Inflector::toPlural('ox'));
	}
	
	function testToSingular() {
		$this->assertEqual('thing', Inflector::toSingular('things'));
		$this->assertEqual('person', Inflector::toSingular('persons'));
		$this->assertEqual('ox', Inflector::toSingular('oxs'));
	}
	
	function testIsPlural() {
		$this->assertTrue(Inflector::isPlural('things'));
		$this->assertTrue(Inflector::isPlural('persons'));
		$this->assertTrue(Inflector::isPlural('oxs'));
		$this->assertFalse(Inflector::isPlural('ox'));
		$this->assertFalse(Inflector::isPlural('person'));
	}
	
	function testToProperCaps() {
		$this->assertEqual('ProperCaps', Inflector::toProperCaps('properCaps'));
		$this->assertEqual('ProperCaps', Inflector::toProperCaps('proper_caps'));
	}
	
	function testToCamelCaps() {
		$this->assertEqual('camelCaps', Inflector::toCamelCaps('CamelCaps'));
		$this->assertEqual('camelCaps', Inflector::toCamelCaps('camel_caps'));
	}
	
	function testToUnderscores() {
		$this->assertEqual('under_scores', Inflector::toUnderscores('UnderScores'));
		$this->assertEqual('under_scores', Inflector::toUnderscores('underScores'));
	}
	
	function testToEnglish() {
		$this->assertEqual('To English', Inflector::toEnglish('toEnglish'));
		$this->assertEqual('To English', Inflector::toEnglish('ToEnglish'));
		$this->assertEqual('To English', Inflector::toEnglish('to_english'));
	}
	
}

?>