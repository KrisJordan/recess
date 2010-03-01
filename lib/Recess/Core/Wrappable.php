<?php
namespace Recess\Core;
/** @addtogroup Core *//** @{ */

/** 
 * A Callable that can be wrapped/decorated with wrapper callables. When invoked the wrapper callables
 * will be invoked in LIFO order and are passed the wrapped callable as its first argument.
 * The arguments the wrappable was invoked with follow. Some additional notes:
 * 
 *  - If a wrapper returns a value without calling the wrapped callable it will short-circuit.
 *  - If a wrapper does not return a value or returns null the unwrapping process continues
 *    until a subsequent wrapper returns or the wrapped callable is called.
 *  - Wrappers may only call the wrapped callable once.
 *  
 *  Wrapper functions may not call
 * the wrapped callable more than once.
 * 
 * A basic example:
 * 
 * @code
 * $add = new Wrappable(function($a,$b){ return $a + $b; });
 * print($add(1, 1));
 * //> 2
 * 
 * $add->wrap(
 * 	function($add, $a, $b) {
 * 		print("Before add($a,$b)\n");
 * 		$result = $add($a,b);
 * 		print("$result\n");
 * 		print("After add($a,$b)\n");
 * 		return $result;
 * 	}
 * );
 * $add(1,1);
 * //> Before add(1,1);
 * //> 2
 * //> After add(1,1);
 * @endcode
 * 
 * More detailed examples:
 * 
 * @include lib/Recess/docs/examples/Recess/Core/Wrappable.php
 * 
 * @author Kris Jordan <http://www.krisjordan.com>
 * @author Copyright &copy; RecessFramework.org 2008-2010 (MIT License)
 * @since Recess PHP Framework 5.3
 */
class Wrappable extends Callable {
/** @} */
	
	/**
	 * @var array
	 */
	protected $wrappers = array();
	
	/**
	 * Decorate the wrapped callable with a wrapper callable.
	 * The wrapper callable's first parameter is the $wrappable.
	 * If the wrappable takes parameters those parameters will be 
	 * passed in the same order following the $wrappable. For example:
	 * 
	 * @code
	 * $wrappable = new Wrappable(function($argA,$argB){});
	 * $wrappable->wrap(function($wrappable,$argA,$argB){return $wrappable($argA,$argB);});
	 * @endcode
	 * 
	 * @param is_callable $wrapper 
	 * @return Wrappable
	 */
	function wrap($wrapper) {
		array_unshift($this->wrappers, $wrapper);
		return $this;
	}
	
	/**
	 * __invoke begins the unwrapping process calling each wrapper in LIFO order.
	 * 
	 * @return mixed
	 */
	function __invoke() {
		$args = func_get_args();
		
		if(empty($this->wrappers)) {
			return call_user_func_array('parent::__invoke', $args);
		} else {
			$wrappers = $this->wrappers;
		}
		
		$wrappers[] = $this->callable;
		reset($wrappers);
		$isCandy = false;
		$lastNonNullReturn = NULL;
		
		$unwrap = function() use (&$unwrap, &$wrappers, &$args, &$isCandy, &$lastNonNullReturn) {			
			$current = current($wrappers);
			if(!is_callable($current)) throw new \Exception('A wrapper has invoked the wrappable more than once.');
			
			$next = next($wrappers);
			
			$argsIn = func_get_args();
			if(!empty($argsIn)) {
				$args = $argsIn;
				$isCandy = false;
			}
			
			$argOffset = 0;
			if(!$isCandy) {
				$isCandy = true;
				if($next !== false) {
					array_unshift($args, $unwrap);
					$argOffset = 1;
				}
			} else {
				// We've reached the wrapped callable
				if($next === false) {
					array_shift($args);
				}
			}
			
			$return = call_user_func_array($current, $args);
			if($return !== NULL) {
				$lastNonNullReturn = $return;
				return $return;
			} else { 
				return $lastNonNullReturn;
			}
		};
		
		do {
			$return = $unwrap();
		} while($return === NULL && current($wrappers) !== false);
		
		return $return;
	}
}