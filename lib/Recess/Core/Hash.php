<?php
namespace Recess\Core; /** @addtogroup Core *//** @{ */

/**
 * An object-oriented wrapper around PHP's array with higher-order methods like map(), 
 * each(), & filter().
 * 
 * @include examples/Recess/Core/Hash.php
 * 
 * To run this example code from the command line:
 * @code php lib/Recess/examples/Recess/Core/Hash.php @endcode
 * 
 * @author Kris Jordan <http://www.krisjordan.com>
 * @author Copyright &copy; RecessFramework.org 2008-2010 (MIT License)
 * @since Recess PHP Framework 5.3
 */
class Hash implements IHash {
/** @} */
	
	protected $elements;
	
	/**
	 * Hashes can be constructed in two ways, with a list of arguments or
	 * with a first-class array.
	 * 
	 * @code
	 * $hash = new Hash(array('key'=>'value'));
	 * $hash = new Hash(1,2,3,4);
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
 	 * Count all elements elements in a Hash with <code>count($hash)</code>. 
	 * Implementation for the SPL <code>Countable</code> interface.
	 * 
	 * @code
	 * $hash = new Hash(1,2,3,4);
	 * echo count($hash);
	 * //> 4
	 * @endcode
	 * 
	 * @see http://php.net/manual/class.countable.php
	 * 
 	 * @return int Number of elements in the Hash.
 	 */
 	function count() {
 		return count($this->elements);
 	}
 	
	/**
	 * Return the current element with <code>current($hash)</code>. 
	 * Implementation for the SPL <code>Iterator</code> interface.
	 * Also used internally during a <code>foreach</code> over elements in the Hash.
	 * 
	 * @see Iterator::current http://php.net/manual/iterator.current.php
	 * 
	 * @return mixed The value of the Hash's internal pointer points to.
	 */
	function current() {
		return current($this->elements);
	}
	
	/**
	 * Invoke a callable on each element of the Hash.
	 * 
	 * @code
	 * $hash = new Hash(1,2,3,4);
	 * $hash->each(function($elem) { echo "-$elem-"; });
	 * //> -1--2--3--4-
	 * @endcode
	 * 
	 * @param $callable 
	 * @return Hash self referential for chaining.
	 */
	function each($callable) {
		each($this->elements, $callable);
		return $this;
	}
	
	/**
	 * Return a Hash containing of the elements of the original Hash
	 * who, when passed to the filter callable, return true.
	 * 
	 * @code
	 * $hash = new Hash(1,2,3,4);
	 * $evens = $hash->filter(function($x) { return $x % 2 === 0; }));
	 * var_export($evens);
	 * //> array(2,4)
	 * @endcode
	 * 
	 * @param $callable
	 * @return IHash
	 */
	function filter($callable) {
		return new Hash(filter($this->elements, $callable));
	}
	
	/**
	 * Return an external Iterator to traverse the elements of the Hash. Implementation
	 * for PHP's IteratorAggregate interface.
	 * 
	 * @see IteratorAggregate http://php.net/manual/class.iteratoraggregate.php
	 * 
	 * @return Iterator http://php.net/manual/class.iterator.php
	 */
 	function getIterator() {
 		return new \ArrayIterator($this->elements);
 	}
	
	/**
	 * Return the key of the current element.
	 * 
	 * @see Iterator::key http://php.net/manual/iterator.key.php
	 * 
	 * @return scalar
	 */
 	function key() {
 		return key($this->elements);
 	}
	
	/**
	 * Return a Hash containing the return values of $callable called on
	 * each element of the Hash.
	 * 
	 * @code
	 * $hash = new Hash(1,2,3,4);
	 * $doubled = $hash->map(function($elem) { return $elem * 2; });
	 * var_export($doubled);
	 * //> array(2,4,6,8);
	 * @endcode
	 * 
	 * @param $callable
	 * @return IHash
	 */
	function map($callable) {
		return new Hash(map($this->elements,$callable));
	}

	/**
	 * Move forward to the next element.
	 * 
	 * @see Iterator::next http://php.net/manual/iterator.next.php
	 */
 	function next() {
 		next($this->elements);
 	}
	
	/**
	 * Whether an offset exists. This method is executed when using isset() or empty().
	 * 
	 * @code
	 * $hash = new Hash(1);
	 * echo isset($hash[0]) ? "t" : "f";
	 * //> t
	 * echo isset($hash[1]) ? "t" : "f";
	 * //> f
	 * @endcode
	 * 
	 * @see ArrayAccess::offsetExists http://php.net/manual/arrayaccess.offsetexists.php
	 * @param mixed $offset An offset to check for.
	 * @return boolean
	 */
	function offsetExists($offset) {
		return isset($this->elements[$offset]);
	}
	
	/**
	 * Return the value at the specified offset. This method is executed when chicking if offset is empty().
	 * 
	 * @see ArrayAccess::offsetGet http://php.net/manual/arrayaccess.offsetget.php
	 * @param mixed $offset The offset to retrieve.
	 * @return mixed
	 */
	function offsetGet($offset) { 
		return $this->elements[$offset];
	}
	
	/**
	 * Assigns a value to the specified offset.
	 * 
	 * @code
	 * $hash = new Hash();
	 * $hash[0] = 1;
	 * @endcode
	 * 
	 * @see ArrayAccess::offsetSet http://php.net/manual/arrayaccess.offsetset.php
	 * @param $offset The offset to assign the value to.
	 * @param $value The value to set.
	 */
	function offsetSet($offset, $value) {
		$this->elements[$offset] = $value;
	}
	
	/**
	 * Unset an offset.
	 * 
	 * @code
	 * $hash = new Hash(1);
	 * unset($hash[0]);
	 * echo isset($hash[0]) ? 't' : 'f';
	 * //> f
	 * @endcode
	 * 
	 * @see ArrayAccess::offsetUnset http://php.net/manual/arrayaccess.offsetunset.php
	 * @param $offset The offset to unset.
	 */
	function offsetUnset($offset) {
		unset($this->elements[$offset]);
	}	
	
	/**
	 * Reduce the values of a hash to a single value using a callable.
	 * 
	 * @code
	 * $hash = new Hash(1,2,3,4);
	 * $sum = $hash->reduce(function($a,$b){return $a + $b;},0);
	 * echo $sum;
	 * //> 10
	 * @endcode
	 * 
	 * @param $callable The function combining elements of the hash.
	 * @param $identity The value to use if the Hash contains 1 or less element.
	 * @return mixed
	 */
	function reduce($callable, $identity) {
		return reduce($this->elements, $callable, $identity);
	}
	
	/**
	 * Rewind the internal pointer to the first element of the Hash.
	 * 
	 * @see Iterator::rewind http://php.net/manual/iterator.rewind.php
	 */
 	function rewind() {
 		return reset($this->elements);
 	}
	
	/**
	 * Return the elements of a Hash as a native PHP array.
	 * 
	 * @return array
	 */
	function toArray() {
		return $this->elements;
	}
	
 	/**
 	 * Checks if the internal pointer's current position is valid. This method is called
 	 * after rewind() and next() to check if the current position is valid.
 	 * 
 	 * @see Iterator::valid http://php.net/manual/iterator.valid.php
 	 * 
 	 * @return boolean
 	 */
 	function valid() {
 		return key($this->elements) !== NULL;
 	}
}