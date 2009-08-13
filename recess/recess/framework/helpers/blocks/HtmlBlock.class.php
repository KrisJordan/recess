<?php
Library::import('recess.framework.helpers.blocks.Block');

/**
 * HtmlBlock is a Block that wraps around static HTML strings.
 * It is often used in conjunction with Buffer which is a helper
 * for automatically buffering output to blocks.
 *  
 * @author Kris Jordan
 */
class HtmlBlock extends Block {
	protected $contents = '';
	
	/**
	 * HtmlBlock can be constructed with an optional string that denotes
	 * its initial contents. i.e. $block = new HtmlBlock('<p>hello world</p>');
	 * @param string Contents optional.
	 */
	public function __construct($contents = '') {
		$this->contents = $contents;
	}
	
	/**
	 * If the block has contents, draw will output the contents and return true. 
	 * If not, it will return false. HtmlBlock's draw takes no arguments and 
	 * will never throw MissingArgumentsException.
	 * 
	 * @see recess/framework/helpers/blocks/Block#draw()
	 */
	public function draw() {
		if($this->contents !== '') {
			echo $this->contents;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Return 
	 * @see recess/recess/recess/framework/helpers/blocks/Block#__toString()
	 */
	public function __toString(){
		return $this->contents;
	}
	
	public function set($contents) {
		$this->contents = $contents;
	}
	
	public function append($contents) {
		$this->contents .= $contents;
	}
	
	public function prepend($contents) {
		$this->contents = $contents . $this->contents;
	}
}
?>