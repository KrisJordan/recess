<?php
namespace Recess\Core;

class Hash implements IHash {
	
	protected $elements;
	
	function __construct($elements = array()) {
		$arguments = func_get_args();
		if(count($arguments) > 1 || !is_array($elements)) {
			$this->elements = $arguments;
		} else {
			$this->elements = $elements;
		}
	}
	
	function toArray() {
		return $this->elements;
	}
	
	function map($callable) {
		return new Hash(map($this->elements,$callable));
	}
	
	function reduce($callable, $identity) {
		return reduce($this->elements, $callable, $identity);
	}
	
	function each($callable) {
		each($this->elements, $callable);
		return $this;
	}
	
	function filter($callable) {
		return new Hash(filter($this->elements, $callable));
	}
	
	function offsetExists($offset) {
		return isset($this->elements[$offset]);
	}
	
	function offsetGet($offset) { 
		return $this->elements[$offset];
	}
	
	function offsetSet($offset, $value) {
		$this->elements[$offset] = $value;
	}
	
	function offsetUnset($offset) {
		unset($this->elements[$offset]);
	}
	
 	/* From \Countable */
 	function count() {
 		return count($this->elements);
 	}
 	
	/* From \Iterator */
	function current() {
		return current($this->elements);
	}
	
 	function key() {
 		return key($this->elements);
 	}
 	
 	function next() {
 		return next($this->elements);
 	}
 	
 	function rewind() {
 		return reset($this->elements);
 	}
 	
 	function valid() {
 		return key($this->elements) !== NULL;
 	}
 	
 	/* From \IteratorAggregate */
 	function getIterator() {
 		return new \ArrayIterator($this->elements);
 	}
 	
}