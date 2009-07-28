<?php
Library::import('recess.framework.helpers.Buffer');

class ArrayBlock extends Block implements Iterator, Countable, ArrayAccess {
	protected $blocks = array();
	protected $valid = false;
	
	/**
	 * Iterate through each block in the array and execute its draw.
	 * @see recess/recess/recess/framework/helpers/blocks/Block#draw()
	 */
	function draw() {
		foreach($this->blocks as $block) {
			$block->draw();
		}
	}
	
	/**
	 * Get the string representation of the ListBlock.
	 * @see recess/recess/recess/framework/helpers/blocks/Block#__toString()
	 */
	function __toString() {
		try {
			Buffer::to($block);
			$this->draw();
			Buffer::end();
			return (string)$block;
		} catch(Exception $e) {
			die($e);
		}
	}
		
	/*
	 * The following methods are in accordance with the Iterator interface
	 */
	public function rewind() {
		$this->valid = (false !== reset($this->blocks));
	}
	
	public function current() {
		return current($this->blocks);
	}

	public function key() {
		return key($this->blocks);
	}
	
	public function next() {
		$this->valid = (FALSE !== next($this->blocks));
	} 
	
	public function valid() {
		return $this->valid; 
	}
	
	/*
	 * The following methods are in accordance with the ArrayAccess interface
	 */
	function offsetExists($index) {
		return isset($this->blocks[$index]);
	}

	function offsetGet($index) {
		if(isset($this->blocks[$index])) {
			return $this->blocks[$index];
		} else {
			throw new OutOfBoundsException();
		}
	}

	function offsetSet($index, $value) {
		$this->blocks[$index] = $value;
	}

	function offsetUnset($index) {
		if(isset($this->blocks[$index])) {
			unset($this->blocks[$index]);
		}
	}
	
	function isEmpty() {
		return !(isset($this[0]) && $this[0] != null);
	}
	
	public function count() {
		return iterator_count($this); 
	}
}
?>