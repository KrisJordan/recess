<?php
Library::import('recess.framework.helpers.Layout');
class LayoutTest extends PHPUnit_Framework_TestCase {
	
	protected $zero = 'zero-input';
	protected $single = 'single-input';
	protected $multi = 'multi-input';
	protected $optional = 'optional-inputs';
	
	function setUp() {
		Layout::addPath(dirname(__FILE__) . '/test-layouts/');
	}
	
	function testSimple() {
		ob_start();
		Layout::draw('simple.php', array());
		$content = ob_get_clean();
		$this->assertEquals('simple', $content);
	}
	
	function testContext() {
		ob_start();
		Layout::draw('context.php', array('context'=>'is valuable'));
		$content = ob_get_clean();
		$this->assertEquals('is valuable', $content);
	}
	
	function testContextFail() {
		ob_start();
		try {
			Layout::draw('context.php', array('contextFail'=>'is valuable'));
			$this->fail('Should throw MissingRequiredInputException');
		} catch(MissingRequiredInputException $e) {
			$this->assertTrue(true);
		} catch(Exception $e) {
			$this->fail('Should throw MissingRequiredInputException. Threw: ' . get_class($e));
		}
	}
	
	function testMultiple() {
		ob_start();
		Layout::draw('multiple.php', array());
		$content = ob_get_clean();
		$this->assertEquals('great success', $content);
	}
	
	function testDefaults() {
		ob_start();
		Layout::draw('defaults.php', array());
		$content = ob_get_clean();
		$this->assertEquals('great success', $content);
	}
	
	function testMiddle() {
		ob_start();
		Layout::draw('middle.php', array());
		$content = ob_get_clean();
		$string = 'child middle master';
		$this->assertEquals($string.$string, $content);
		
	}
}

?>