<?php
namespace recess\core;

class Chain {
	
	protected $closure;
	
	protected $chain = array();
	
	function __construct($closure) {
		$this->closure = $closure;
	}
	
	function add($closure) {
		array_unshift($this->chain, $closure);
		return $this;
	}
	
	function __invoke() {
		$args = func_get_args();
		
		$chain = $this->chain;
		$chain[] = $this->closure;
		reset($chain);
		$unshift = true;
		
		$nextCall = function() use (&$nextCall, &$chain, &$args, &$unshift) {			
			$current = current($chain);
			$next = next($chain);
			
			$argsIn = func_get_args();
			if(!empty($argsIn)) {
				$args = $argsIn;
				$unshift = true;
			}
			
			if($unshift) {
				$unshift = false;
				if($next !== false) {
					array_unshift($args, $nextCall);
				}
			} else {
				if($next === false) {
					array_shift($args);
				}
			}
			
			switch(count($args)) {
				case 0:	return $current();
				case 1:	return $current($args[0]);
				case 2: return $current($args[0],$args[1]);
				case 3: return $current($args[0],$args[1],$args[2]);
				case 4: return $current($args[0],$args[1],$args[2],$args[3]);
				case 5: return $current($args[0],$args[1],$args[2],$args[3],$args[4]);
				case 6: return $current($args[0],$args[1],$args[2],$args[3],$args[4],$args[5]);
				default: return call_user_func_array($current,$args);
			}
		};
		
		do {
			$return = $nextCall();
		} while($return === null && current($chain) !== false);
		
		return $return;
	}
}