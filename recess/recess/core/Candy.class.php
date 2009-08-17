<?php
namespace recess\core;
/**
 * Turn callables into delicious Candy.
 * Candied callables that can be wrapped in the all the decorators you desire.
 * 
 * Warning: Candy is sweet but decadent. When consumed in excess your profiling
 * will fill out in unflattering ways. Programs may also be prone to
 * clarity cavities when wrappers are laying around all over the place. When in
 * doubt, put down the Candy and stick to your meat and potatoes.
 * 
 * =========
 *   Usage
 * =========
 * Basic Wrapping
 * $butterscotch = new Candy(function() { echo "Mmm, Butterscotch!"; });
 * $butterscotch->wrap(
 * 	function($candy){ echo "Hmm, what's in this gold foil wrapper?"; $candy(); }
 * );
 * $butterscotch();
 * // Output: Hmm, what's in this gold foil wrapper? Mmm, Butterscotch!
 * 
 * Short Circuit Returns
 * $trickOrTreat = new Candy(function($isTrick) 
 * 		{ return $isTrick ? 'trick' : 'treat!'; }
 * );
 * echo $trickOrTreat(true);  // trick
 * echo $trickOrTreat(false); // treat
 * $trickOrTreat->wrap(
 * 	function($candy, $isTrick) 
 * 		{ return $isTrick ? 'no tricks here!' : $candy($isTrick); }
 * );
 * echo $trickOrTreat(true);  // no tricks here!
 * echo $trickOrTreat(false); // treat!
 * 
 * Automatic Unwrapping
 * Note: When a wrapper does not return, or returns null, 
 *       the unwrapping is not short-circuited.
 * $auto = new Candy(function() { echo "But I'm still here!"; });
 * $auto->wrap(function($candy) { echo "I didn't candy! "; });
 * $auto();
 * // Output: I didn't candy! But I'm still here!
 * 
 * Automatic Argument Passing
 * $mint = new Candy(function($print) { echo $print; });
 * $mint->wrap(
 * 	function($candy, $print) 
 * 		{ $candy("It's $print"); $candy(); $candy('Gum!'); }
 * );
 * $mint('Doublemint! ');
 * // Output: It's Doublemint! Doublemint! Gum!
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @since Recess 5.3
 * @copyright RecessFramework.org 2009
 * @license MIT
 */
class Candy {
	
	/**
	 * @var Closure or Callable
	 */
	protected $callable;
	
	/**
	 * @var array
	 */
	protected $wrappers = array();
	
	/**
	 * Turn a callable into Candy with the constructor.
	 * 
	 * @param $callable Callable the function being wrapped.
	 * @return Candy
	 */
	function __construct($callable) {
		if(is_callable($callable)) {
			$this->callable = $callable;
		} else {
			throw new \Exception('Candy can only be used on callables.');
		}
	}
	
	/**
	 * Wrap the candy with a callback wrapper. The wrapper's first parameter
	 * must always be the $candy callback to invoke the underlying candy.
	 * If the candied callable has parameters those parameters must be 
	 * specified in the same order as the candied callable following the candy
	 * callback.
	 * 
	 * @param $wrapper 
	 * @return Candy
	 */
	function wrap($wrapper) {
		array_unshift($this->wrappers, $wrapper);
		return $this;
	}
	
	/**
	 * __invoke begins the unwrapping of candy with any callablees that wrap
	 * the candied callable.
	 * 
	 * @return variable
	 */
	function __invoke() {
		$args = func_get_args();
		
		// Base case optimization
		// 	If there are no wrappers, let's have some candy!
		if(empty($this->wrappers)) {
			$callable = $this->callable;
			if(is_string($callable) || $callable instanceof \Closure) {
				switch(count($args)) {
					case 0:	return $callable();
					case 1:	return $callable($args[0]);
					case 2: return $callable($args[0],$args[1]);
					case 3: return $callable($args[0],$args[1],$args[2]);
					case 4: return $callable($args[0],$args[1],$args[2],$args[3]);
					case 5: return $callable($args[0],$args[1],$args[2],$args[3],$args[4]);
					default: return call_user_func_array($callable,$args);
				}
			} else {
				return call_user_func_array($callable, $args);
			}
		} else {
			$wrappers = $this->wrappers;
		}
		
		$wrappers[] = $this->callable;
		reset($wrappers);
		$isCandy = false;
		
		$unwrap = function() use (&$unwrap, &$wrappers, &$args, &$isCandy) {			
			$current = current($wrappers);
			$next = next($wrappers);
			
			$argsIn = func_get_args();
			if(!empty($argsIn)) {
				$args = $argsIn;
				$isCandy = false;
			}
			
			if(!$isCandy) {
				$isCandy = true;
				if($next !== false) {
					array_unshift($args, $unwrap);
				}
			} else {
				// We've reached the candy
				if($next === false) {
					array_shift($args);
				}
			}
			
			if(is_string($current) || $current instanceof \Closure) {
				switch(count($args)) {
					case 0:	return $current();
					case 1:	return $current($args[0]);
					case 2: return $current($args[0],$args[1]);
					case 3: return $current($args[0],$args[1],$args[2]);
					case 4: return $current($args[0],$args[1],$args[2],$args[3]);
					case 5: return $current($args[0],$args[1],$args[2],$args[3],$args[4]);
					default: return call_user_func_array($current,$args);
				}
			} else {
				return call_user_func_array($current, $args);
			} 
		};
		
		do {
			$return = $unwrap();
		} while($return === null && current($wrappers) !== false);
		
		return $return;
	}
}