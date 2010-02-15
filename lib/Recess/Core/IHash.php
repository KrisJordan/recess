<?php
namespace Recess\Core;
/** @addtogroup Core *//** @{ */

/**
 * 
 * @see ArrayAccess http://php.net/manual/class.arrayaccess.php
 * @see Countable http://php.net/manual/class.countable.php
 * @see Iterator http://php.net/manual/class.iterator.php
 * @see IteratorAggregate http://php.net/manual/class.iteratoraggregate.php
 */
interface IHash extends \ArrayAccess, \Countable, \Iterator, \IteratorAggregate {
/** @} */
	
	/* Hash Specific Functions */
	function toArray();
	
	function map($callable);
	function reduce($callable,$identity);
	function each($callable);
	function filter($callable);
	
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