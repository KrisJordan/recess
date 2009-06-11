<?php
Library::import('recess.framework.AbstractHelper');

/**
 * The Layout helper enables template inheritance with a system of slots
 * filled by blocks. Parent templates define slots that can be filled by 
 * child templates with blocks. Slots and blocks are named with strings.
 * Content in a child template that appears outside of any blocks will
 * implicitly create a 'body' block.
 * 
 * <code>
 * <?php
 * // master.php
 * Layout::slot('foo');
 * echo 'This is the slot\'s default content.';
 * Layout::slotEnd();
 * 
 * Layout::slot('body');
 * echo 'This is the default body content.';
 * Layout::slotEnd();
 * ?>
 * 
 * <?php
 * // child.php
 * Layout::extend('master');
 * Layout::block('foo');
 * echo 'This overrides the parent\'s foo slot content.';
 * Layout::blockEnd();
 * 
 * echo 'By default this fills the body slot.';
 * ?>
 * </code>
 * @author Kris Jordan
 * @author Joshua Paine
 */
class Layout extends AbstractHelper {
	
	const DEFAULT_BLOCK = 'body';
	
	protected static $extendStack = array();
	
	protected static $blockStack = array();
	protected static $blockMap = array();
	
	protected static $slotStack = array();
	
	protected static $app;
	
	/**
	 * Initialize the Layout helper with a View.
	 * 
	 * @param	AbstractView The view this helper is helping.
	 */
	public static function init(AbstractView $view) {
		$response = $view->getResponse();
		self::$app = $response->meta->app;
	}
	
	/**
	 * Extend a parent layout. Referenced from the views/ directory in
	 * the current application.
	 * 
	 * @param	string	The layout to extend.
	 */
	public static function extend($layout) {
		if(!empty(self::$extendStack)) {
			throw new RecessFrameworkException('Nesting extends is not allowed.', 1);
		}
		
		$layout = self::$app->getViewsDir() . $layout;
		if(strrpos($layout, '.') < strrpos($layout, '/')) {
			$layout .= '.php';
		}

		if(!file_exists($layout)) {
			throw new RecessFrameworkException('Extended layout ('.$layout.') does not exist.', 1);
		}

		array_push(self::$extendStack, $layout);
		ob_start();
	}
	
	/**
	 * Open a block that will fill a slot on a parent template. Any content sent 
	 * to the output buffer between block/blockEnd will be used to fill the slot.
	 * 
	 * @param	string	The name of the slot this block fills.
	 */
	public static function block($title) {
		if(empty(self::$extendStack)) {
			throw new RecessFrameworkException('Blocks are only valid when extending a layout using Layout::extend()', 1);
		}
		
		if(!empty(self::$blockStack)) {
			throw new RecessFrameworkException('Nesting blocks is not allowed. You must end a block with Layout::blockEnd() before starting a new block.', 1);
		}
		
		array_push(self::$blockStack, $title);
		ob_start();
	}
	
	/**
	 * Mark the end of a slot. Must be called after an opening block().
	 */
	public static function blockEnd() {
		if(empty(self::$blockStack)) {
			throw new RecessFrameworkException('Block end encountered without a preceding Layout::block() to open the block.', 1);
		}
		
		$blockName = array_pop(self::$blockStack);
		if(!isset(self::$blockMap[$blockName])) {
			self::$blockMap[$blockName] = ob_get_clean();
		} else {
			ob_end_clean();
		}
	}
	
	/**
 	 * Helper method for blocks whose content is a single string. Prints 
 	 * string between a block/blockEnd pair to avoid verbosity in a child
 	 * layout.
 	 * 
 	 * @param string The name of the slot this block fills.
 	 * @param string The value of the block.
	 */
	public static function blockAssign($title, $value) {
		self::block($title);
		echo $value;
		self::blockEnd();
	}

	/**
	 * Marks the beging of a slot in a parent template to be optionally
	 * filled by a child. Any content output between this slot open and
	 * slotEnd will be used as the default content of a slot should the
	 * extending template not fill it with a block.
	 * 
	 * @param string The name of the slot.
	 */
	public static function slot($title) {
		if(!empty(self::$slotStack)) {
			throw new RecessFrameworkException('Nesting slots is not allowed. You must end a slot with Layout::slotEnd() before starting a new slot.', 1);
		}
		
		array_push(self::$slotStack, $title);
		ob_start();
	}
	
	/**
	 * Marks the end of a slot. In effect this outputs the content of a slot.
	 */
	public static function slotEnd() {
		if(empty(self::$slotStack)) {
			throw new RecessFrameworkException('Slot end encountered without a preceding Layout::slot() to open the slot.', 1);
		}
		
		$slotName = array_pop(self::$slotStack);
		if(isset(self::$blockMap[$slotName])) {
			ob_end_clean();
			echo self::$blockMap[$slotName];
			unset(self::$blockMap[$slotName]);
		} else {
			ob_end_flush();
		}
	}
	
	/**
	 * Helper method for a common scenario of a 'middle'
	 * template filling some additional information in a slot
	 * and re-opening the slot for a child to append to. Example:
	 * 
	 * <code>
	 * // master.php
	 * 	<html>
	 * 	  <head>
	 * 		<title>Master<?php Layout::slot('Title') ?><?php Layout::slotEnd(); ?></title>
	 * 	  </head>
	 * </html>
	 * </code>
	 * 
	 * <code>
	 * <?php 
	 * // section.php
	 * Layout::extend('master');
	 * Layout::slotAppend('title', ' > Section');
	 * ?>
	 * </code>
	 * 
	 * <code>
	 * <?php
	 * // page.php
	 * Layout::extend('section');
	 * Layout::slotAppend('title', ' > Page');
	 * ?>
	 * </code>
	 * 
	 * Result is: <title>Master > Section > Page</title>
	 * 
	 * @param string The slot to append to.
	 * @param string The value to append to the slot.
	 */
	public static function slotAppend($title, $value) {
		self::block($title);
		echo $value;
		self::slot($title);
		self::slotEnd();
		self::blockEnd();
	}
	
	/**
	 * End a template extension and process the parent. 
	 * Called automatically by RecessView but can be called manually.
	 */
	public static function extendEnd() {
		if(!empty(self::$extendStack)) {
			if(!isset(self::$blockMap[Layout::DEFAULT_BLOCK])) {
				self::$blockMap[Layout::DEFAULT_BLOCK] = ob_get_clean();
			} else {
				ob_end_clean();
			}
			
			$parent = array_pop(self::$extendStack);
			include($parent);
			
			self::extendEnd();	
		}
	}
	
}

?>
