<?php
Library::import('recess.framework.helpers.blocks.HtmlBlock');

class HtmlBlockTest extends PHPUnit_Framework_TestCase {
	
	public function testConstructor() {
		$string = 'foo';
		$emptyBlock = new HtmlBlock();
		$stringBlock = new HtmlBlock($string);
		$this->assertEquals('', (string)$emptyBlock);
		$this->assertEquals($string, (string)$stringBlock);
	}
	
	public function testDrawEmpty() {
		$block = new HtmlBlock();
		ob_start();		
		$result = $block->draw();
		$content = ob_get_clean();
		
		$this->assertFalse($result);
		$this->assertEquals('', $content);
	}
	
	public function testDrawNonEmpty() {
		$string = 'foo';
		$block = new HtmlBlock($string);
		ob_start();		
		$result = $block->draw();
		$content = ob_get_clean();
		
		$this->assertTrue($result);
		$this->assertEquals($string, $content);
	}
	
	public function testToString(){
		// Same as constructor test.
		$string = 'foo';
		$emptyBlock = new HtmlBlock();
		$stringBlock = new HtmlBlock($string);
		$this->assertEquals('', (string)$emptyBlock);
		$this->assertEquals($string, (string)$stringBlock);
	}
	
	public function testSet() {
		$string = 'foo';
		$emptyBlock = new HtmlBlock();
		$emptyBlock->set($string);
		$this->assertEquals($string, (string)$emptyBlock);
	}
	
	public function testAppend() {
		$string = 'foo';
		$stringBlock = new HtmlBlock($string);
		$stringBlock->append($string . '?');
		$this->assertEquals($string . $string . '?', (string)$stringBlock);
	}
	
	public function testPrepend() {
		$string = 'foo';
		$stringBlock = new HtmlBlock($string);
		$stringBlock->prepend($string . '?');
		$this->assertEquals($string . '?' . $string , (string)$stringBlock);
	
	}
	
}

?>