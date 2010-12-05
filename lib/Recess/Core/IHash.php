<?php
namespace Recess\Core; /** @addtogroup Core *//** @{ */

/**
 * The interface for array/iterator-like objects used throughout Recess.
 * 
 * @see ArrayAccess http://php.net/manual/class.arrayaccess.php
 * @see Countable http://php.net/manual/class.countable.php
 * @see Iterator http://php.net/manual/class.iterator.php
 * @see IteratorAggregate http://php.net/manual/class.iteratoraggregate.php
 * 
 * @author Kris Jordan <http://www.krisjordan.com>
 * @author Copyright &copy; RecessFramework.org 2008-2010 (MIT License)
 * @since Recess PHP Framework 5.3
 */
interface IHash extends \ArrayAccess, \Countable, \Iterator {
/** @} */
	
	/**
	 * Invoke a callable on each element of the IHash.
	 * 
	 * @param $callable 
	 * @return IHash self referential for chaining.
	 */	
	function each($callable);

	/**
	 * Return an IHash containing of the elements of the original IHash
	 * who, when passed to the filter callable, return true.
	 * 
	 * @param $callable
	 * @return IHash
	 */
	function filter($callable);
	
	/**
	 * Return an IHash containing the return values of $callable called on
	 * each element of the IHash.
	 * 
	 * @param $callable
	 * @return IHash
	 */
	function map($callable);
	
	/**
	 * Reduce the values of an IHash to a single value using a callable.
	 * 
	 * @param $callable The function combining elements of the hash.
	 * @param $identity The value to use if the Hash contains 1 or less element.
	 * @return mixed
	 */
	function reduce($callable,$identity);
	
	/**
	 * Return the elements of the IHash as a native PHP array.
	 * 
	 * @return array
	 */
	function toArray();
	
	/* 
	From \ArrayAccess
	function offsetExists($offset);	
	function offsetGet($offset);
	function offsetSet($offset, $value);
	function offsetUnset($offset);
	
 	From \Countable
 	function count();
 	
	From \Iterator
	function current();
 	function key();
 	function next();
 	function rewind();
 	function valid();
 	
 	From \IteratorAggregate
 	function getIterator();
 	*/
}
