<?php
Library::import('recess.http.AcceptsList');

class AcceptsListTest extends PHPUnit_Framework_TestCase {	
	protected $mediaString = 'text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5';
	protected $media2String = 'image/gif, image/jpeg, image/pjpeg, application/x-ms-application, application/vnd.ms-xpsdocument, application/xaml+xml, application/x-ms-xbap, application/x-shockwave-flash, application/x-silverlight-2-b2, application/x-silverlight, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, */*';
	protected $languageString = 'en-us,en;q=0.5';
	protected $charsetString = 'ISO-8859-1,utf-8;q=0.7,*;q=0.7';
	protected $specificityString = 'text/*,text/html,text/plain;q=0.9,text/*;q=0.9,*/*;q=0.9';
	
	function testMediaString() {
		$acceptsString = new AcceptsList($this->mediaString);
		$this->assertEquals(array('text/xml','application/xml','application/xhtml+xml','image/png'), $acceptsString->next());
		$this->assertEquals(array('text/html'), $acceptsString->next());
		$this->assertEquals(array('text/plain'), $acceptsString->next());
		$this->assertEquals(array('*/*'), $acceptsString->next());
		$this->assertEquals(false, $acceptsString->next());
	}

	function testMedia2String() {
		$acceptsString = new AcceptsList($this->media2String);
		$this->assertEquals(array('image/gif', 'image/jpeg', 'image/pjpeg', 'application/x-ms-application', 'application/vnd.ms-xpsdocument', 'application/xaml+xml', 'application/x-ms-xbap', 'application/x-shockwave-flash', 'application/x-silverlight-2-b2', 'application/x-silverlight', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/msword'), $acceptsString->next());
		$this->assertEquals(array('*/*'), $acceptsString->next());
		$this->assertEquals(false, $acceptsString->next());
	}
	
	function testLanguageString() {
		$acceptsString = new AcceptsList($this->languageString);
		$this->assertEquals(array('en-us'), $acceptsString->next());
		$this->assertEquals(array('en'), $acceptsString->next());
		$this->assertEquals(false, $acceptsString->next());	
	}
	
	function testCharsetString() {
		$acceptsString = new AcceptsList($this->charsetString);
		$this->assertEquals(array('ISO-8859-1'), $acceptsString->next());
		$this->assertEquals(array('utf-8'), $acceptsString->next());
		$this->assertEquals(array('*'), $acceptsString->next());
		$this->assertEquals(false, $acceptsString->next());	
	}
	
	function testSpecificity() {
		$acceptsString = new AcceptsList($this->specificityString);
		$this->assertEquals(array('text/html'), $acceptsString->next());
		$this->assertEquals(array('text/*'), $acceptsString->next());
		$this->assertEquals(array('text/plain'), $acceptsString->next());
		$this->assertEquals(array('text/*'), $acceptsString->next());
		$this->assertEquals(array('*/*'), $acceptsString->next());
		$this->assertEquals(false, $acceptsString->next());	
	}
}
?>