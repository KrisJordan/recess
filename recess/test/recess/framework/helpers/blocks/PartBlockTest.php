<?php
Library::import('recess.framework.helpers.Part');
Library::import('recess.framework.helpers.blocks.PartBlock');

class PartBlockTest extends PHPUnit_Framework_TestCase {
	
	protected $zero = 'zero-input';
	protected $single = 'single-input';
	protected $multi = 'multi-input';
	protected $optional = 'optional-inputs';
	
	function setUp() { 
		Part::addPath(dirname(__FILE__) . '/../test-parts/');
	}
	
	function testDrawZeroArgs() {
		ob_start();
		$block = new PartBlock($this->zero);
		$block->draw();
		$content = ob_get_clean();
		$this->assertEquals('no input', $content);
	}
	
	function testDrawZeroArgsPassOne() {
		ob_start();
		$block = new PartBlock($this->zero);
		$block->draw('ignored');
		$content = ob_get_clean();
		$this->assertEquals('no input', $content);
	}
	
	function testDrawSingle() {
		$pass = 'foo';
		ob_start();
		$block = new PartBlock($this->single, $pass);
		$block->draw();
		$content = ob_get_clean();
		$this->assertEquals($pass, $content);
	}
	
	function testDrawSingleWithTwoArgs() {
		$pass = 'foo';
		ob_start();
		$block = new PartBlock($this->single, $pass, $pass);
		echo $block;
		$content = ob_get_clean();
		$this->assertEquals($pass, $content);
	}
	
	function testDrawSingleFail() {
		try {
			$block = new PartBlock($this->single);
			$block->draw();
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
		$block = new PartBlock($this->multi);
		$block->draw($one, $two, $three);
		$content = ob_get_clean();
		$this->assertEquals('onethreeonethree', $content);
	}
	
	function testDrawMultiFail() {
		try {
			Part::block($this->multi)->draw();
			$this->fail('Should throw MissingRequiredDrawArgumentsException. Passed.');
		} catch (MissingRequiredDrawArgumentsException $e) {
			$this->assertTrue(true);
		} catch (Exception $e) {
			echo $e;
			$this->fail('Should throw MissingRequiredDrawArgumentsException. Got: ' . get_class($e));
		}
		
		try {
			Part::block($this->multi, 'one')->draw();
			$this->fail('Should throw MissingRequiredDrawArgumentsException. Passed.');
		} catch (MissingRequiredDrawArgumentsException $e) {
			$this->assertTrue(true);
		} catch (Exception $e) {
			echo $e;
			$this->fail('Should throw MissingRequiredDrawArgumentsException.');
		}
		
		try {
			Part::block($this->multi, 'one', 2)->draw();	
			$this->fail('Should throw MissingRequiredDrawArgumentsException. Passed.');
		} catch (MissingRequiredDrawArgumentsException $e) {
			$this->assertTrue(true);
		} catch (Exception $e) {
			$this->fail('Should throw MissingRequiredDrawArgumentsException. Got: ' . get_class($e));
		}
	}
	
	function testDrawOptional() {
		ob_start();
		Part::block($this->optional)->draw();
		$content = ob_get_clean();
		$this->assertEquals('default', $content);
		
		ob_start();
		Part::block($this->optional)->draw('foo');
		$content = ob_get_clean();
		$this->assertEquals('non-default', $content);
	}
	
	function testInputTypeFail() {
		try{
			Part::block($this->optional, 1)->draw();
			$this->fail('Should throw InputTypeCheckException');
		} catch(InputTypeCheckException $e) {
			$this->assertTrue(true);
		} catch(Exception $e) {
			print $e->getMessage();
			$this->fail('Should throw InputTypeCheckException, Got: ' . get_class($e));
		}
	}
	
	function testOutOfOrderAssignment() {
		$one = 'one';
		$two = 2;
		$three = 'three';
		
		ob_start();
		$block = new PartBlock($this->multi);
		$block->set('second',$two)->set('first',$one)->set('third',$three)->draw();
		$content = ob_get_clean();
		$this->assertEquals('onethreeonethree', $content);
	}
	
	function testCurrying() {
		$one = 'one';
		$two = 2;
		$three = 'three';
		
		ob_start();
		$block = new PartBlock($this->multi, $one);
		$block->draw($two, $three);
		$content = ob_get_clean();
		$this->assertEquals('onethreeonethree', $content);
	}
	
	function testCurryingFail() {
		$one = 'one';
		$two = 2;
		$three = 'three';
		
		try {
			$block = new PartBlock($this->multi, $one);	
			$block->set('second',$two)->draw($three);
			$this->fail('Should throw InputTypeCheckException');
		} catch(InputTypeCheckException $e) {
			$this->assertTrue(true);
		} catch(Exception $e) {
			$this->fail('Should throw InputTypeCheckException, Got: ' . get_class($e));
		}
	}
	
	function testCurryingOptional() {
		$one = 'one';
		$two = 2;
		$three = 'three';
		
		ob_start();
		$block = new PartBlock($this->multi, $one);
		$block->set('third',$three)->draw($two);
		$content = ob_get_clean();
		$this->assertEquals('onethreeonethree', $content);
	}
	
}

?>