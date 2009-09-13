<?php
set_include_path(__DIR__ . PATH_SEPARATOR . get_include_path());

function callable($callback) {
	if(!is_callable($callback)) {
		throw new Exception('First parameter $callable must be a valid is_callable value.');
	}
	
	if(!is_array($callback)) {
		return $callback;
	}
	
	if(is_string($callback[0])) {
		return function() use ($callback) {
			$args = func_get_args();
			switch(count($args)) {
				case 0:	return $callback[0]::$callback[1]();
				case 1:	return $callback[0]::$callback[1]($args[0]);
				case 2: return $callback[0]::$callback[1]($args[0],$args[1]);
				case 3: return $callback[0]::$callback[1]($args[0],$args[1],$args[2]);
				case 4: return $callback[0]::$callback[1]($args[0],$args[1],$args[2],$args[3]);
				case 5: return $callback[0]::$callback[1]($args[0],$args[1],$args[2],$args[3],$args[4]);
				default: return call_user_func_array($callback,$args);
			}
		};
	} else {
		return function() use ($callback) {
			$args = func_get_args();
			switch(count($args)) {
				case 0:	return $callback[0]->$callback[1]();
				case 1:	return $callback[0]->$callback[1]($args[0]);
				case 2: return $callback[0]->$callback[1]($args[0],$args[1]);
				case 3: return $callback[0]->$callback[1]($args[0],$args[1],$args[2]);
				case 4: return $callback[0]->$callback[1]($args[0],$args[1],$args[2],$args[3]);
				case 5: return $callback[0]->$callback[1]($args[0],$args[1],$args[2],$args[3],$args[4]);
				default: return call_user_func_array($callback,$args);
			}
		};
	}
}

// Include the Autoloader
include 'recess/lang/ClassLoader.class.php';

// Register Autoload Function
spl_autoload_register('recess\lang\ClassLoader::load');