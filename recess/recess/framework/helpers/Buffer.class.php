<?php
Library::import('recess.framework.helpers.blocks.Block');
Library::import('recess.framework.helpers.blocks.HtmlBlock');
Library::import('recess.framework.helpers.blocks.ListBlock');
Library::import('recess.framework.AbstractHelper');

/**
 * Buffer is a helper class that acts as a factory for
 * HtmlBlocks. Buffer and blocks are often used in conjunction
 * with layouts as an easy mechanism for transferring chunks of
 * HTML from a child template to a parent Layout.
 * 
 * Buffer can be used to fill unempty HtmlBlocks,
 * overwrite HtmlBlocks, or append/prepend to them. Here are some
 * example usages:
 * 
 * Buffer::to($block);
 * echo 'hello world';
 * Buffer::end();
 * // $block is now an HtmlBlock with contents 'hello world'
 * 
 * Buffer::append($block);
 * echo '!<br />';
 * Buffer::end();
 * // $block is now an HtmlBlock with contents 'hello world!<br />'
 * 
 * Buffer::to($block);
 * print_r($block);
 * Block::end();
 * // $block is still 'hello world!<br />'
 * 
 * Buffer::to($block, Buffer::OVERWRITE);
 * echo 'overwritten';
 * Buffer::end();
 * // $block is now 'overwritten'
 * 
 * echo $block;
 * // overwritten
 * 
 * @author Kris Jordan
 */
abstract class Buffer extends AbstractHelper {
	
	const NORMAL = 0;
	const OVERWRITE = 1;
	const APPEND = 2;
	const PREPEND = 3;
	
	/** STATIC MEMBERS **/
	
	private static $bufferBlocks = array();
	private static $bufferModes = array();
	
	/**
	 * Begin output buffering to the block passed by reference. If the
	 * reference is set to Null a new HtmlBlock will be assigned to the 
	 * reference.
	 * 
	 * @param HtmlBlock or Null - The block the buffer will fill.
	 * @param int Optional - mode used to fill block.
	 */
	public static function to(&$block, $mode = self::NORMAL) {
		self::modalStart($block, $mode);
	}
	
	/**
	 * Buffer will append to the provided HtmlBlock. If null this will
	 * create a new block, not fail.
	 * 
	 * @param HtmlBlock The block to append to.
	 */
	public static function appendTo(&$block) {
		self::modalStart($block, self::APPEND);
	}
	
	/**
	 * Buffer will append to the provided HtmlBlock. If null this will
	 * create a new block, not fail.
	 * 
	 * @param HtmlBlock The block to append to.
	 */
	public static function prependTo(&$block) {
		self::modalStart($block, self::PREPEND);
	}

	/**
	 * Internal helper method for starting a new buffer.
	 * 
	 * @param HtmlBlock The block to append to.
	 */
	private static function modalStart(&$block, $mode) {
		if($block === null) {
			$block = new HtmlBlock();
		}
		array_push(self::$bufferBlocks, $block);
		array_push(self::$bufferModes, $mode);
		ob_start();
	}
	
	/**
	 * End the output buffer, clear contents, and assign contents
	 * to the block passed by reference to start the buffer. Also
	 * returns the block.
	 * 
	 * @return The final block.
	 */
	public static function end() {
		if(empty(self::$bufferBlocks)) {
			throw new RecessFrameworkException('Buffer ended without corresponding Buffer::to($block).', 2);
		}		
		$buffer = ob_get_clean();
		$mode = array_pop(self::$bufferModes);
		$block = array_pop(self::$bufferBlocks);
		switch($mode) {
			case self::NORMAL: 
				if((string)$block === '') {
					$block->set($buffer);
				}
				break;
			case self::OVERWRITE:
				$block->set($buffer);
				break;
			case self::APPEND:
				$block->append($buffer);
				break;
			case self::PREPEND:
				$block->prepend($buffer);
				break;
		}
		return $block;
	}
	
}
?>