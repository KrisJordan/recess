<?php
Library::import('recess.framework.helpers.exceptions.MissingRequiredDrawArgumentsException');
Library::import('recess.framework.helpers.exceptions.BlockToStringException');

/**
 * A Block is a fundamental unit of UI in Recess.
 * 
 * @author Kris Jordan
 * @since 0.20
 */
abstract class Block {
	
	/**
	 * Output the contents of the block. Returns true if successful or false if
	 * the block is empty. Sub-classes of block may optionally require parameters
	 * be passed to draw. If these parameters are not passed as expected the
	 * sub-class must throw an exception of type MissingRequiredDrawArgumentsException.
	 * 
	 * @return boolean
	 */
	public abstract function draw();
	
	/**
	 * Return the contents of this block as a string. If the block is not fully
	 * formed (i.e., it's draw requires an argument), then __toString will throw
	 * an exception of type BlockToStringException.
	 * 
	 * @return string
	 */
	public abstract function __toString();
	
}
?>