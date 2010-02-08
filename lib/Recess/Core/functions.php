<?php
namespace Recess\Core;
/** @addtogroup Core *//** @{ */

function map($array, $mapFn) {
	$results = array();
	foreach($array as $value) {
		$results[] = $mapFn($value);
	}
	return $results;
}

function reduce($array, $combineFn, $identity) {
	if(count($array) <= 1) {
		$out = $identity;
	} else if(count($array) > 1) {
		$out = array_shift($array);
		do {
			$next = array_shift($array);
			$out = $combineFn($out, $next);
		} while(!empty($array));
	}
	return $out;
}

function each($array, $fn) {
	foreach($array as $value) {
		$fn($value);
	}
	return $array;
}

function filter($array, $filterFn) {
	$results = array();
	foreach($array as $key => $value) {
		if($filterFn($value) === true) {
			$results[$key] = $value;
		}
	}
	return $results;
}
/**@}*/