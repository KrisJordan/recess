<?php
Library::import('recess.http.Accepts');

class AcceptsTest extends PHPUnit_Framework_TestCase {	
	protected $acceptChrome, $acceptFirefox, $acceptIE;

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
	}
	
	function testChromeContentTypes() {
		$this->assertEquals('html', $this->acceptChrome->nextFormat());
		$this->assertEquals('xml', $this->acceptChrome->nextFormat());
		$this->assertEquals('png', $this->acceptChrome->nextFormat());
		$this->assertEquals('text', $this->acceptChrome->nextFormat());
		$this->assertEquals('*', $this->acceptChrome->nextFormat());
		$this->assertEquals(false, $this->acceptChrome->nextFormat());
	}
	
	function testFirefoxContentTypes() {
		$this->assertEquals('html', $this->acceptFirefox->nextFormat());
		$this->assertEquals('xml', $this->acceptFirefox->nextFormat());
		$this->assertEquals('*', $this->acceptFirefox->nextFormat());
		$this->assertEquals(false, $this->acceptFirefox->nextFormat());
	}
	
	function testIEContentTypes() {
		$this->assertEquals('jpg', $this->acceptIE->nextFormat());
		$this->assertEquals('gif', $this->acceptIE->nextFormat());
		$this->assertEquals('*', $this->acceptIE->nextFormat());
		$this->assertEquals(false, $this->acceptIE->nextFormat());
	}
}
?>