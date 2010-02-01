<?php
Library::import('recess.framework.helpers.Buffer');
Library::import('recess.framework.helpers.blocks.HtmlBlock');

class BufferTest extends PHPUnit_Framework_TestCase {
	
	function testTo() {
		$content = 'Hello world.';
		Buffer::to($start);
		echo $content;
		Buffer::end();
		$this->assertType('Block', $start);
		$this->assertType('HtmlBlock', $start);
		$this->assertEquals($content, (string)$start);
	}
	
	function testNesting() {
		$firstLevel = 'First';
		$secondLevel = 'Second';
		Buffer::to($first);
			Buffer::to($second);
				echo $secondLevel;
			Buffer::end();
			echo $firstLevel;
		Buffer::end();
		
		$this->assertEquals($firstLevel, (string)$first);
		$this->assertEquals($secondLevel, (string)$second);
	}
	
	function testStartDefault() {
		$expected = 'This should still be the content.';
		Buffer::to($block);
		echo $expected;
		Buffer::end();
		Buffer::to($block);
		echo 'Should not override.';
		Buffer::end();
		$this->assertType('Block', $block);
		$this->assertEquals($expected, (string)$block);
	}
	
	function testStartOverwrite() {
		$original = 'This should still be the content.';
		Buffer::to($block);
		echo $original;
		Buffer::end();
		Buffer::to($block, Buffer::OVERWRITE);
		$expected = 'This will override.';
		echo $expected;
		Buffer::end();
		$this->assertType('Block', $block);
		$this->assertEquals($expected, (string)$block);
	}
	
	function testAppend() {
		$original = 'Original';
		$append = 'Appended';
		$block = new HtmlBlock($original);
		Buffer::appendTo($block);
		echo $append;
		Buffer::end();
		$this->assertType('Block', $block);
		$this->assertEquals($original.$append, (string)$block);
	}
	
	function testPrepend() {
		$original = 'Original';
		$prepend = 'Prepend';
		$block = new HtmlBlock($original);
		Buffer::prependTo($block);
		echo $prepend;
		Buffer::end();
		$this->assertType('Block', $block);
		$this->assertEquals($prepend.$original, (string)$block);
	}
	
}

?>