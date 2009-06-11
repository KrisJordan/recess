<?php
Library::import('recess.http.Accepts');

class AcceptsTest extends PHPUnit_Framework_TestCase {	
	protected $acceptChrome, $acceptFirefox, $acceptIE, $acceptOverride;

	function setup() {						
		$this->acceptChrome
					 = new Accepts(
							array(	'ACCEPT' => 'text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5',
									'ACCEPT_LANGUAGE' => 'en-US,en',
									'ACCEPT_ENCODING' => 'gzip,deflate,bzip2,sdch',
									'ACCEPT_CHARSET' => 'ISO-8859-1,*,utf-8',)
							);
							
		$this->acceptFirefox
					= new Accepts(
						array(	'ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
								'ACCEPT_LANGUAGE' => 'en-us,en;q=0.5',
								'ACCEPT_ENCODING' => 'gzip,deflate',
								'ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7', )
					);
					
		$this->acceptIE
					= new Accepts(
						array(	'ACCEPT' => 'image/gif, image/jpeg, image/pjpeg, application/x-ms-application, application/vnd.ms-xpsdocument, application/xaml+xml, application/x-ms-xbap, application/x-shockwave-flash, application/x-silverlight-2-b2, application/x-silverlight, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, */*',
								'ACCEPT_LANGUAGE' => 'en-us',
								'ACCEPT_ENCODING' => 'gzip, deflate',
							 )
					);
					
		$this->acceptOverride
					= new Accepts(
						array(	'ACCEPT' => 'image/gif, image/jpeg, image/pjpeg, application/x-ms-application, application/vnd.ms-xpsdocument, application/xaml+xml, application/x-ms-xbap, application/x-shockwave-flash, application/x-silverlight-2-b2, application/x-silverlight, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, */*',
								'ACCEPT_LANGUAGE' => 'en-us',
								'ACCEPT_ENCODING' => 'gzip, deflate',
							 )
					);
	}
	
	function testChromeContentTypes() {
		$this->assertEquals('html', $this->acceptChrome->nextFormat());
		$this->assertEquals('xml', $this->acceptChrome->nextFormat());
		$this->assertEquals('png', $this->acceptChrome->nextFormat());
		$this->assertEquals('text', $this->acceptChrome->nextFormat());
		
		// Now we go down the list of others to expand */*
		$this->assertEquals('json', $this->acceptChrome->nextFormat());
		$this->assertEquals('js', $this->acceptChrome->nextFormat());
		$this->assertEquals('css', $this->acceptChrome->nextFormat());
		$this->assertEquals('rss', $this->acceptChrome->nextFormat());
		$this->assertEquals('yaml', $this->acceptChrome->nextFormat());
		$this->assertEquals('atom', $this->acceptChrome->nextFormat());
		$this->assertEquals('jpg', $this->acceptChrome->nextFormat());
		$this->assertEquals('gif', $this->acceptChrome->nextFormat());
		$this->assertEquals('form', $this->acceptChrome->nextFormat());
		$this->assertEquals('url-form', $this->acceptChrome->nextFormat());
		$this->assertEquals('csv', $this->acceptChrome->nextFormat());
		$this->assertEquals(false, $this->acceptChrome->nextFormat());
	}
	
	function testFirefoxContentTypes() {
		$this->assertEquals('html', $this->acceptFirefox->nextFormat());
		$this->assertEquals('xml', $this->acceptFirefox->nextFormat());
		
		// Now we go down the list of others to expand */*
		$this->assertEquals('json', $this->acceptFirefox->nextFormat());
		$this->assertEquals('js', $this->acceptFirefox->nextFormat());
		$this->assertEquals('css', $this->acceptFirefox->nextFormat());
		$this->assertEquals('rss', $this->acceptFirefox->nextFormat());
		$this->assertEquals('yaml', $this->acceptFirefox->nextFormat());
		$this->assertEquals('atom', $this->acceptFirefox->nextFormat());
		$this->assertEquals('text', $this->acceptFirefox->nextFormat());
		$this->assertEquals('png', $this->acceptFirefox->nextFormat());
		$this->assertEquals('jpg', $this->acceptFirefox->nextFormat());
		$this->assertEquals('gif', $this->acceptFirefox->nextFormat());
		$this->assertEquals('form', $this->acceptFirefox->nextFormat());
		$this->assertEquals('url-form', $this->acceptFirefox->nextFormat());
		$this->assertEquals('csv', $this->acceptFirefox->nextFormat());
		$this->assertEquals(false, $this->acceptFirefox->nextFormat());
	}
	
	function testIEContentTypes() {
		$this->assertEquals('gif', $this->acceptIE->nextFormat());
		$this->assertEquals('jpg', $this->acceptIE->nextFormat());
		
		// Now we go down the list of others to expand */*
		$this->assertEquals('html', $this->acceptIE->nextFormat());
		$this->assertEquals('xml', $this->acceptIE->nextFormat());
		$this->assertEquals('json', $this->acceptIE->nextFormat());
		$this->assertEquals('js', $this->acceptIE->nextFormat());
		$this->assertEquals('css', $this->acceptIE->nextFormat());
		$this->assertEquals('rss', $this->acceptIE->nextFormat());
		$this->assertEquals('yaml', $this->acceptIE->nextFormat());
		$this->assertEquals('atom', $this->acceptIE->nextFormat());
		$this->assertEquals('text', $this->acceptIE->nextFormat());
		$this->assertEquals('png', $this->acceptIE->nextFormat());
		$this->assertEquals('form', $this->acceptIE->nextFormat());
		$this->assertEquals('url-form', $this->acceptIE->nextFormat());
		$this->assertEquals('csv', $this->acceptIE->nextFormat());
		$this->assertEquals(false, $this->acceptIE->nextFormat());
	}
	
	function testReset() {
		$this->testIEContentTypes();
		$this->acceptIE->resetFormats();
		$this->testIEContentTypes();
	}
	
	function testOverrideContentTypes() {
		$this->acceptOverride->forceFormat('xml');
		$this->assertEquals('xml', $this->acceptOverride->nextFormat());
		$this->acceptOverride->resetFormats();
		$this->assertEquals('xml', $this->acceptOverride->nextFormat());
		$this->assertEquals(false, $this->acceptOverride->nextFormat());
	}
	
	function testBlank() {
		$blank = new Accepts(array());
		$this->assertEquals(false, $blank->nextFormat());
	}
}
?>