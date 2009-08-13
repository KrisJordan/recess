<?php
Library::import('recess.framework.helpers.AssertiveTemplate');
Library::import('recess.framework.helpers.Buffer');

/**
 * Layout is a style of AssertiveTemplate that allows child templates
 * to 'extend' parent Layouts. Context is transferred from child to parent
 * by matching variables that exist in the child and are registered
 * as an input to the parent. Thus layouts must specify any required and
 * optional inputs they expect to be passed.
 * 
 * Parent layouts require a '.layout.php' extension.
 * 
 * @author Kris
 */
class Layout extends AssertiveTemplate {
	private static $parentStack = array();
	private static $debugTraces = array();
	
	/**
	 * Outputs a child template. Pass the template name (with extension) and
	 * an associative array context of variables to be passed to the child.
	 * 
	 * @param string The filename of the template, with extension, relative to AssertiveTemplate paths.
	 * @param array The associative array of context the child template expects.
	 * @return boolean Returns true on success.
	 */
	public static function draw($template, $context) {
		Buffer::to($body);
		$context = self::includeTemplate($template, $context);
		Buffer::end();
		
		if(empty(self::$parentStack)) {
			echo $body;
			return true;
		} else {
			if(!isset($context['body'])) {
				$context['body'] = $body;
			}
			while($parent = array_pop(self::$parentStack)) {
				try{
					$parentInputs = self::getInputs($parent, 'Layout');
				}catch(RecessFrameworkException $e) {
	//				if(RecessConf::$mode == RecessConf::DEVELOPMENT) {
						$trace = array_pop(self::$debugTraces);
						throw new RecessErrorException('Extended layout does not exist.', 0, 0, $trace[0]['file'], $trace[0]['line'], $trace[0]['args']);
	//				} else {
	//					throw $e;
	//				}
				}
				$context = array_intersect_key($context, $parentInputs);
				$context = self::includeTemplate($parent, $context);
			}
	//		if(RecessConf::$mode == RecessConf::DEVELOPMENT) {
				array_pop(self::$debugTraces);
	//		}
			return true;
		}
	}
	
	/**
	 * Used by child templates so indicate they 'extend' a parent layout which
	 * is to be included and assume all requested context from a child. Parent
	 * layouts are required to use a '.layout.php' extension.
	 * 
	 * @param string The name of the layout being extended without the '.layout.php' extension.
	 */
	public static function extend($assertiveTemplate) {
		if(strpos($assertiveTemplate,'/layout') !== strlen($assertiveTemplate) - 7) {
			array_push(self::$parentStack, $assertiveTemplate . '.layout.php');
		} else {
			array_push(self::$parentStack, $assertiveTemplate . '.php');
		}
		//if(RecessConf::$mode == RecessConf::DEVELOPMENT) {
			$trace = debug_backtrace();
			array_pop($trace);
			array_push(self::$debugTraces, $trace);
		//}
	}
}
?>