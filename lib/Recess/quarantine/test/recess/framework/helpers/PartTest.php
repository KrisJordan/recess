<?php
Library::import('recess.framework.helpers.Part');
Library::import('recess.framework.helpers.blocks.HtmlBlock');
Library::import('recess.framework.helpers.exceptions.MissingRequiredDrawArgumentsException');

class PartTest extends PHPUnit_Framework_TestCase {
	
	protected $zero = 'zero-input';
	protected $single = 'single-input';
	protected $multi = 'multi-input';
	protected $optional = 'optional-inputs';
	
	function setUp() {
		Part::addPath(dirname(__FILE__) . '/test-parts/');
	}
	
	function testDrawZeroArgs() {
		ob_start();
		Part::draw($this->zero);
		$content = ob_get_clean();
		$this->assertEquals('no input', $content);
	}
	
	function testDrawZeroArgsPassOne() {
		ob_start();
		Part::draw($this->zero, 'ignored');
		$content = ob_get_clean();
		$this->assertEquals('no input', $content);
	}
	
	function testDrawSingle() {
		$pass = 'foo';
		ob_start();
		Part::draw($this->single, $pass);
		$content = ob_get_clean();
		$this->assertEquals($pass, $content);
	}
	
	function testDrawSingleWithTwoArgs() {
		$pass = 'foo';
		ob_start();
		Part::draw($this->single, $pass, $pass);
		$content = ob_get_clean();
		$this->assertEquals($pass, $content);
	}
	
	function testDrawSingleFail() {
		try {
			Part::draw($this->single);	
			$this->fail('Should throw MissingRequiredDrawArgumentsException. Passed.');
		} catch (MissingRequiredDrawArgumentsException $e) {
			$this->assertTrue(true);
		} catch (Exception $e) {
			echo $e;
			$this->fail('Should throw MissingRequiredDrawArgumentsException. Got: ' . get_class($e));
		}
	}
	
	function testDrawMulti() {
		$one = 'one';
		$two = 2;
		$three = 'three';
		
		ob_start();
		Part::draw($this->multi, $one, $two, $three);
		$content = ob_get_clean();
		$this->assertEquals('onethreeonethree', $content);
	}
	
	function testDrawMultiFail() {
		try {
			Part::draw($this->multi);	
			$this->fail('Should throw MissingRequiredDrawArgumentsException. Passed.');
		} catch (MissingRequiredDrawArgumentsException $e) {
			$this->assertTrue(true);
		} catch (Exception $e) {
			echo $e;
			$this->fail('Should throw MissingRequiredDrawArgumentsException. Got: ' . get_class($e));
		}
		
		try {
			Part::draw($this->multi, 'one');	
			$this->fail('Should throw MissingRequiredDrawArgumentsException. Passed.');
		} catch (MissingRequiredDrawArgumentsException $e) {
			$this->assertTrue(true);
		} catch (Exception $e) {
			echo $e;
			$this->fail('Should throw MissingRequiredDrawArgumentsException.');
		}
		
		try {
			Part::draw($this->multi, 'one', 2);	
			$this->fail('Should throw MissingRequiredDrawArgumentsException. Passed.');
		} catch (MissingRequiredDrawArgumentsException $e) {
			$this->assertTrue(true);
		} catch (Exception $e) {
			$this->fail('Should throw MissingRequiredDrawArgumentsException. Got: ' . get_class($e));
		}
	}
	
	function testDrawOptional() {
		ob_start();
		Part::draw($this->optional);
		$content = ob_get_clean();
		$this->assertEquals('default', $content);
		
		ob_start();
		Part::draw($this->optional, 'foo');
		$content = ob_get_clean();
		$this->assertEquals('non-default', $content);
	}
	
	function testInputTypeFail() {
		try{
			Part::draw($this->optional, 1);
			$this->fail('Should throw InputTypeCheckException');
		} catch(InputTypeCheckException $e) {
			$this->assertTrue(true);
		} catch(Exception $e) {
			print $e->getMessage();
			$this->fail('Should throw InputTypeCheckException, Got: ' . get_class($e));
		}
	}
	
}

?>