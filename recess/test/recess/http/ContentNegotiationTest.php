<?php
Library::import('recess.http.ContentNegotiation');

class ContentNegotiationTest extends PHPUnit_Framework_TestCase {
	
	function testAccepts() {
		$negotiator = 
			new ContentNegotiation(
					array(	'ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
							'ACCEPT_LANGUAGE' => 'en-us,en;q=0.5',
							'ACCEPT_ENCODING' => 'gzip,deflate',
							'ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7')
					);
		
		$this->assertEquals('things', Inflector::toPlural('thing'));
		$this->assertEquals('persons', Inflector::toPlural('person'));
		$this->assertEquals('oxs', Inflector::toPlural('ox'));
	}
	
}

?>