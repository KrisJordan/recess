<?php
Library::import('recess.http.AcceptsString');

class AcceptsStringTest extends PHPUnit_Framework_TestCase {	
	protected $mediaString = 'text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5';
	protected $media2String = 'image/gif, image/jpeg, image/pjpeg, application/x-ms-application, application/vnd.ms-xpsdocument, application/xaml+xml, application/x-ms-xbap, application/x-shockwave-flash, application/x-silverlight-2-b2, application/x-silverlight, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, */*';
	protected $languageString = 'en-us,en;q=0.5';
	protected $charsetString = 'ISO-8859-1,utf-8;q=0.7,*;q=0.7';
	protected $specificityString = 'text/*,text/html,text/plain;q=0.9,text/*;q=0.9,*/*;q=0.9';
	
	function testMediaString() {
		$acceptsString = new AcceptsString($this->mediaString);
		$this->assertEquals($acceptsString->next(), array('text/xml','application/xml','application/xhtml+xml','image/png'));
		$this->assertEquals($acceptsString->next(), array('text/html'));
		$this->assertEquals($acceptsString->next(), array('text/plain'));
		$this->assertEquals($acceptsString->next(), array('*/*'));
		$this->assertEquals($acceptsString->next(), false);
	}

	function testMedia2String() {
		$acceptsString = new AcceptsString($this->media2String);
		$this->assertEquals($acceptsString->next(), array('image/gif', 'image/jpeg', 'image/pjpeg', 'application/x-ms-application', 'application/vnd.ms-xpsdocument', 'application/xaml+xml', 'application/x-ms-xbap', 'application/x-shockwave-flash', 'application/x-silverlight-2-b2', 'application/x-silverlight', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/msword'));
		$this->assertEquals($acceptsString->next(), array('*/*'));
		$this->assertEquals($acceptsString->next(), false);
	}
	
	function testLanguageString() {
		$acceptsString = new AcceptsString($this->languageString);
		$this->assertEquals($acceptsString->next(), array('en-us'));
		$this->assertEquals($acceptsString->next(), array('en'));
		$this->assertEquals($acceptsString->next(), false);	
	}
	
	function testCharsetString() {
		$acceptsString = new AcceptsString($this->charsetString);
		$this->assertEquals($acceptsString->next(), array('ISO-8859-1'));
		$this->assertEquals($acceptsString->next(), array('utf-8'));
		$this->assertEquals($acceptsString->next(), array('*'));
		$this->assertEquals($acceptsString->next(), false);	
	}
	
	function testSpecificity() {
		$acceptsString = new AcceptsString($this->specificityString);
		$this->assertEquals($acceptsString->next(), array('text/html'));
		$this->assertEquals($acceptsString->next(), array('text/*'));
		$this->assertEquals($acceptsString->next(), array('text/plain'));
		$this->assertEquals($acceptsString->next(), array('text/*'));
		$this->assertEquals($acceptsString->next(), array('*/*'));
		$this->assertEquals($acceptsString->next(), false);	
	}
}
?>