<?php
namespace Recess\Core;
/** @addtogroup Core *//** @{ */

/**
 * Invoke a callable one-by-one over an array of values.
 * 
 * @code
 * each(array(1,2,3), function($x) { echo $x+$x," "; });
 * //> 2 4 6
 * @endcode
 * 
 * @param $array array of mixed values
 * @param $callable is_callable
 */
function each($array, $callable) {
	foreach($array as $value) {
		$callable($value);
	}
}

/**
 * Invoke a callable one-by-one $array's values, if result is
 * true the value is included in the returned array, if not true 
 * it is filtered out.
 * 
 * @code
 * var_export(
 * 	filter(array(1,2,3,4), function($x) { return $x % 2 === 0; })
 * );
 * //> array(2,4)
 * @endcode
 * 
 * @param $array array of mixed values
 * @param $callable is_callable
 * @return array
 */
function filter($array, $callable) {
	$results = array();
	foreach($array as $key => $value) {
		if($callable($value) === true) {
			$results[$key] = $value;
		}
	}
	return $results;
}

/**
 * Invoke a callable one-by-one over an array of values and return an array
 * containing the result of each invocation.
 * 
 * @code
 * var_export(
 * 	map(array(1,2,3,4), function($x) { return $x * $x; })
 * );
 * //> array(1,4,9,16)
 * @endcode
 * 
 * @param $array array of mixed values
 * @param $callable is_callable
 * @return array
 */
function map($array, $callable) {
	$results = array();
	foreach($array as $key => $value) {
		$results[$key] = $callable($value);
	}
	return $results;
}

/**
 * Reduce an array of values using a callable that combines the array's
 * values. If the array contains 0 or 1 elements the identity value 
 * will be returned.
 * 
 * @code
 * echo reduce(array(1,2,3,4), function($x,$y) { return $x + $y; }, 1);
 * //> 10
 * @endcode
 * 
 * @param $array array of mixed values
 * @param $callable is_callable
 * @param $identity mixed return value if $array contains 0 or 1 elements
 * @return array
 */
function reduce($array, $callable, $identity) {
	if(count($array) <= 1) {
		$out = $identity;
	} else if(count($array) > 1) {
		$out = array_shift($array);
		do {
			$next = array_shift($array);
			$out = $callable($out, $next);
		} while(!empty($array));
	}
	return $out;
}
/**@}*/