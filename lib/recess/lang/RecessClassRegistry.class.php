<?php
/**
 * Recess! Framework Class Registry
 * This static store maintains global information about
 * classes which extend RecessClass.
 * 
 * @author Kris Jordan
 */

abstract class RecessClassRegistry {
	static protected $registry = array();
	
	/**
	 * Return the RecessClassInfo for provided RecessClass instance.
	 *
	 * @param RecessClass $recessObject
	 * @return RecessClassInfo
	 */
	static function infoForObject($recessObject) {
		$class = get_class($recessObject);
		
		if(isset(self::$registry[$class])) {
			return self::$registry[$class];
		} else {
			if(is_a($recessObject, 'RecessClass')) {
				return self::$registry[$class] = call_user_func(array($class, 'getRecessClassInfo'));
			} else {
				throw new RecessException('RecessClassRegistry only retains information on classes derived from RecessClass. Class of type "' . $class . '" given.', get_defined_vars());
			}
		}
	}
	
}

?>