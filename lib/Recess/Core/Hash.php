<?php
namespace Recess\Core;
/** @addtogroup Core *//** @{ */

/**
 * An object-oriented PHP array with higher-order methods like map(), each(), & filter(). 
 * Hash implements IHash (which extends ArrayAccess) so it can be used as an array.
 * IHash also requires higher-order methods: 
 * 	- map()
 * 	- reduce()
 *	- each()
 *	- filter()
 * 
 * @include examples/Recess/Core/Hash.php
 * 
 * To run this example code from the command line:
 * @code php lib/Recess/examples/Recess/Core/Hash.php @endcode
 * 
 * @author Kris Jordan <http://krisjordan.com>
 * @copyright RecessFramework.org 2008-2010
 * @license MIT
 * @since Recess 5.3
 */
class Hash implements IHash {
/** @} */
	
	/** @var array The PHP array the Hash wraps. */
	protected $elements;
	
	/**
	 * Hashes can be constructed in two ways, with a list of arguments or
	 * with a first-class array.
	 * 
	 * @code
	 * new Hash(array('key'=>'value'));
	 * new Hash(1,2,3,4);
	 * @endcode
	 * 
	 * @param $elements or list
	 */
	function __construct($elements = array()) {
		$arguments = func_get_args();
		$this->elements = isset($arguments[1]) || !is_array($elements) ?
			$arguments : $elements;
	}
	
	/**
	 * Convert the Hash into a PHP array.
	 * @return array
	 */
	function toArray() {
		return $this->elements;
	}
	
	/**
	 * Map a callable over the values in the hash.
	 * 
	 * @param $callable
	 * @return Hash
	 */
	function map($callable) {
		return new Hash(map($this->elements,$callable));
	}
	
	/**
	 * Reduce the values of a hash to a single value using a callable.
	 * 
	 * @param $callable
	 * @param $identity The value to use if the Hash contains 1 or less element.
	 * @return varies
	 */
	function reduce($callable, $identity) {
		return reduce($this->elements, $callable, $identity);
	}
	
	/**
	 * Invoke a callable on each element of the hash.
	 * 
	 * @param $callable 
	 * @return Hash self referential for chaining.
	 */
	function each($callable) {
		each($this->elements, $callable);
		return $this;
	}
	
	/**
	 * Returns a new hash consisting of the elements of the old hash
	 * who, when passed to the callable, return true.
	 * 
	 * Example:
	 * @code
	 * $hash = new Hash(1,2,3,4);
	 * print_r($hash->filter(function($x) { return $x % 2 === 0; }));
	 * // array ( 2, 4 )
	 * @endcode
	 * 
	 * @param $callable
	 * @return Hash
	 */
	function filter($callable) {
		return new Hash(filter($this->elements, $callable));
	}
	
	/**
	 * Implementation of \ArrayAccess interface that enables:
	 * is_set($hash[0])
	 * 
	 * @see \ArrayAccess::offsetExists
	 * @param $offset
	 * @return varies
	 */
	function offsetExists($offset) {
		return isset($this->elements[$offset]);
	}
	
	/**
	 * Implementation of \ArrayAccess interface that enables:
	 * echo $hash[0];
	 * 
	 * @see \ArrayAccess::offsetGet
	 * @param $offset
	 * @return varies
	 */
	function offsetGet($offset) { 
		return $this->elements[$offset];
	}
	
	/**
	 * Implementation of \ArrayAccess interface that enables:
	 * $hash[0] = 1;
	 * 
	 * @see \ArrayAccess::offsetSet
	 * @param $offset
	 * @param $value
	 */
	function offsetSet($offset, $value) {
		$this->elements[$offset] = $value;
	}
	
	/**
	 * Implementation of \ArrayAccess interface that enables:
	 * unset($hash[0]);
	 * 
	 * @see \ArrayAccess::offsetUnset
	 * @param $offset
	 */
	function offsetUnset($offset) {
		unset($this->elements[$offset]);
	}
	
 	/** 
 	 * Implementation of \Countable interface that enables:
 	 * count($hash);
 	 * 
 	 * @see \Countable::count
 	 */
 	function count() {
 		return count($this->elements);
 	}
 	
	/** @see \Iterator::current */
	function current() {
		return current($this->elements);
	}
	
	/** @see \Iterator::key */
 	function key() {
 		return key($this->elements);
 	}
 	
 	/** @see \Iterator::next */
 	function next() {
 		return next($this->elements);
 	}
 	
 	/** @see \Iterator::rewind */
 	function rewind() {
 		return reset($this->elements);
 	}
 	
 	/** @see \Iterator::valid */
 	function valid() {
 		return key($this->elements) !== NULL;
 	}
 	
 	/* @see \IteratorAggregate::getIterator */
 	function getIterator() {
 		return new \ArrayIterator($this->elements);
 	}
 	
}