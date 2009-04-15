<?php
Library::import('recess.framework.AbstractHelper');

class Layout extends AbstractHelper {
	
	const DEFAULT_BLOCK = 'body';
	
	protected static $extendStack = array();
	
	protected static $blockStack = array();
	protected static $blockMap = array();
	
	protected static $slotStack = array();
	
	protected static $app;
	
	public static function init(AbstractView $view) {
		$response = $view->getResponse();
		self::$app = $response->meta->app;
	}
	
	public static function extend($layout) {
		if(!empty(self::$extendStack)) {
			throw new RecessFrameworkException('Nesting extends is not allowed.', 1);
		}
		
		array_push(self::$extendStack, $layout);
		ob_start();
	}
	
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
	
	public static function blockAssign($title, $value) {
		self::block($title);
		echo $value;
		self::blockEnd();
	}
	
	public static function slot($title) {
		if(!empty(self::$slotStack)) {
			throw new RecessFrameworkException('Nesting slots is not allowed. You must end a slot with Layout::slotEnd() before starting a new slot.', 1);
		}
		
		array_push(self::$slotStack, $title);
		ob_start();
	}
	
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
	
	public static function slotAppend($title, $value) {
		self::block($title);
		echo $value;
		self::slot($title);
		self::slotEnd();
		self::blockEnd();
	}
	
	public static function extendEnd() {
		if(!empty(self::$extendStack)) {
			if(!isset(self::$blockMap[Layout::DEFAULT_BLOCK])) {
				self::$blockMap[Layout::DEFAULT_BLOCK] = ob_get_clean();
			} else {
				ob_end_clean();
			}
			
			$parent = array_pop(self::$extendStack);
			include(self::$app->getViewsDir() . $parent . '.php');
			self::extendEnd();	
		}
	}
	
}

?>