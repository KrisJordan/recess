<?php
Library::import('recess.framework.helpers.exceptions.InputTypeCheckException');

/**
 * The sequence Block type. Extends from Block so it acts as a block
 * that can be composed of other Blocks. Its draw/to string iterates
 * through contained blocks in-order and calls their draw method. Can
 * form trees by adding ListBlocks to a ListBlock.
 * 
 * Only blocks whose draw method can be called without arguments should
 * be added to a ListBlock else expect exceptions to be thrown when 
 * drawing.
 * 
 * The instance methods: append, prepend, and add, as well as class
 * methods: constructor, and make, can take strings or Blocks passed
 * as a variable number of arguments or as an array.
 * 
 * @author Kris Jordan
 */
class ListBlock extends Block {

	const APPEND = 0;
	const PREPEND = 1;
	protected $blocks = array();

	/**
	 * Create a new listblock. Variable parameters can be either
	 * a sequence of Block or string arguments, or an array of Blocks
	 * or strings.
	 */
	function __construct() {
		$args = func_get_args();
		$this->add($args, self::APPEND);
	}

	/**
	 * Iterate through each block in the list and execute its draw.
	 * @see recess/recess/recess/framework/helpers/blocks/Block#draw()
	 */
	function draw() {
		foreach($this->blocks as $block) {
			$block->draw();
		}
	}
	
	/**
	 * Factory method for instantiating ListBlock without using new.
	 * @return ListBlock
	 */
	static function make() {
		$args = func_get_args();
		$blocks = new ListBlock();
		$blocks->add($args, self::APPEND);
		return $blocks;
	}
	
	/**
	 * Append Blocks / strings to the ListBlock
	 * @return ListBlock for chaining.
	 */
	function append() {
		$args = func_get_args();
		$this->add($args, self::APPEND);
		return $this;
	}

	/**
	 * Prepend Blocks / strings to the ListBlock
	 * @return ListBlock for chaining.
	 */
	function prepend() {
		$args = func_get_args();
		$this->add($args, self::PREPEND);
		return $this;
	}
	
	/**
	 * protected helper method for adding elements
	 * to the head or teail of the list.
	 */
	protected function add($args, $mode) {
		if(!empty($args)) {
			if(count($args) == 1) {
				if(is_array($args[0])) {
					$blocks = $args[0];
				} else {
					$blocks = array($args[0]);
				}
			} else {
				$blocks = $args;
			}
		} else {
			return;
		}
		// Flip the blocks if prepending.
		if($mode == self::PREPEND) {
			$blocks = array_reverse($blocks);
		}
		foreach($blocks as $block) {
			if(is_string($block)) {
				$block = new HtmlBlock($block);
			}
			if(!$block instanceof Block) {
				throw new InputTypeCheckException('ListBlock accepts only Block instances and strings, given: ' . gettype($block), 2);
			}
			switch($mode) {
				case self::APPEND:
					$this->blocks[] = $block;
					break;
					
				case self::PREPEND:
					array_unshift($this->blocks, $block);
					break;
			}
		}
	}

	/**
	 * Get the string representation of the ListBlock.
	 * @see recess/recess/recess/framework/helpers/blocks/Block#__toString()
	 */
	function __toString() {
		Buffer::to($block);
		$this->draw();
		Buffer::end();
		return (string)$block;
	}
}
?>