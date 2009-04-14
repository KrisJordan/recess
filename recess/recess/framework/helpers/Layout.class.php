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
			die('error: can\'t nest extends');
		}
		
		array_push(self::$extendStack, $layout);
		ob_start();
	}
	
	public static function block($title) {
		if(empty(self::$extendStack)) {
			die('error: not extending');
		}
		
		if(!empty(self::$blockStack)) {
			die('error: cant nest blocks');
		}
		
		array_push(self::$blockStack, $title);
		ob_start();
	}
	
	public static function blockEnd() {
		if(empty(self::$blockStack)) {
			die('not in a block!');
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
			die('can\'t nest slots');
		}
		
		array_push(self::$slotStack, $title);
		ob_start();
	}
	
	public static function slotEnd() {
		if(empty(self::$slotStack)) {
			die('not in a slot');
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