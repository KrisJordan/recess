<?php
Library::import('recess.framework.helpers.blocks.ListBlock');
Library::import('recess.framework.helpers.blocks.HtmlBlock');

class ListBlockTest extends PHPUnit_Framework_TestCase {
	function test__construct($blocks = array()) {
		$blocks = new ListBlock('one','two',new HtmlBlock('three'));
		$this->assertEquals('onetwothree',(string)$blocks);
	}

	function testDraw() {
		$blocks = new ListBlock('one','two');
		ob_start();
		$blocks->draw();
		$content = ob_get_clean();
		$this->assertEquals('onetwo', $content);
	}
	
	function testMake() {
		$block = ListBlock::make('one','two');
		$this->assertEquals('onetwo', (string)$block);
	}
	
	function testAppend() {
		$block = ListBlock::make('one','two');
		$block->append('three','four');
		$block->append(array('five','six'));
		$block->append('seven');
		$this->assertEquals('onetwothreefourfivesixseven', (string)$block);
	}
	
	function testPrepend() {
		$block = ListBlock::make('one','two');
		$block->prepend('three','four');
		$block->prepend(array('five','six'));
		$block->prepend('seven');
		$this->assertEquals('sevenfivesixthreefouronetwo', (string)$block);
	}
	
	function testAppendFail() {
		$block = new ListBlock();
		try {
			$block->append(false);
			$this->fail('Should throw exception!');
		} catch(InputTypeCheckException $e) {
			$this->assertTrue(true);
		} catch(Exception $e) {
			
		}
	}
}

?>