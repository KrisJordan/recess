<?php
require_once($_ENV['dir.recess'] . 'diagnostics/Diagnostics.class.php');
require_once($_ENV['dir.recess'] . 'cache/Cache.class.php');

/**
 * Used to include class files into the system
 * Used over straight require's as a level of indirection to provide
 * opportunity for more interesting include/require behaviors.
 * Uses PHP's __autoload for lazy loading.
 * 
 * @author Kris Jordan
 * @todo Document this class well.
 * @todo Allow framework to register packages/shortcuts? i.e.: Library::import('recess.framework.models.Model') vs. Library::import('recess','Model')
 */
class Library {
	static private $classesByClass = array();	// = array( 'Inflector' => array( 'recess.lang.Inflector', 0 );
	static private $classesByFull = array();	// = 'recess.lang.Inflector'
	static private $dirtyClasses = false;
	
	static private $paths = array();
	static private $dirtyPaths = false;
	
	static private $loaded = array('Libary' => true);
	
	const dotSeparator = '.';
	const pathSeparator = '/';
	
	const CLASSES_X_CLASS_CACHE_KEY = 'Library::$classesByClass';
	const CLASSES_X_FULL_CACHE_KEY = 'Library::$classesByFull';
	const PATHS_CACHE_KEY = 'Library::$paths';
	
	const NAME = 0;
	const PATH = 1;
	
	static function init() {		
		$paths = Cache::get(self::PATHS_CACHE_KEY);
		if($paths !== false) self::$paths = $paths;
		
		$classesByClass = Cache::get(self::CLASSES_X_CLASS_CACHE_KEY);
		if($classesByClass !== false) self::$classesByClass = $classesByClass;
		
		$classesByFull = Cache::get(self::CLASSES_X_FULL_CACHE_KEY);
		if($classesByFull !== false) self::$classesByFull = $classesByFull;
		
		self::$dirtyPaths = false;
		self::$dirtyClasses = false;
	}
	
	static function shutdown() {
		if(self::$dirtyPaths) {
			Cache::set(self::PATHS_CACHE_KEY, self::$paths);
		}
		
		if(self::$dirtyClasses) {
			Cache::set(self::CLASSES_X_CLASS_CACHE_KEY, self::$classesByClass);
			Cache::set(self::CLASSES_X_FULL_CACHE_KEY, self::$classesByFull);
		}
	}
	
	static function addClassPath($newPath) {
		if(!in_array($newPath, self::$paths)) {
			self::$paths[] = $newPath;
			self::$dirtyPaths = true;
		}
	}
	
	static function addClass($fullName) {
		$className = self::getClassName($fullName);
		self::$classesByFull[$fullName] = $className;
		self::$classesByClass[$className] = array( $fullName, -1 );
	}
	
	static function import($fullName, $forceLoad = false) {
		if(!isset(self::$classesByFull[$fullName])) {
			self::addClass($fullName);
		}
		
		$className = self::$classesByFull[$fullName];
	
		if(!isset(self::$loaded[$className])) {
			self::$loaded[$className] = false;
		}
		
		if($forceLoad) {
			self::load($className);
		}
	}
	
	static function importAndInstantiate($fullName) {
		// TODO: REDUCE DUPLICATION WITH IMPORT
		if(isset(self::$classesByFull[$fullName])) {
			$className = self::$classesByFull[$fullName];
		} else {
			$className = self::getClassName($fullName);
			self::addClass($fullName, $className);
		}
	
		if(!isset(self::$loaded[$className])) {
			self::$loaded[$className] = false;
		}
		
		return new $className;
	}
	
	static function classExists($fullName) {
		if(!isset(self::$classesByFull[$fullName])) {
			self::addClass($fullName);
		}
		
		$class = self::$classesByFull[$fullName];
		$pathIndex = self::$classesByClass[$class][self::PATH];
		$file = str_replace(self::dotSeparator,self::pathSeparator, $fullName) . '.class.php';
		if($pathIndex == -1) {
			foreach(self::$paths as $index => $path) {
				@include_once($path . $file);
				if(class_exists($class, false) || interface_exists($class, false)) {
					self::$dirtyClasses = true;
					self::$classesByClass[$class][self::PATH] = $index;
					self::$loaded[$class] = true;
					return true;
				}
			}
			return false;
		} else {
			$path = self::$paths[$pathIndex] . $file;
			include_once($path);
			self::$loaded[$class] = true;
		}
		return class_exists($class, false) || interface_exists($class, false);
	}
	
//	static function loadViaMemcache($className) {
//		if(class_exists($className) || interface_exists($className)) return true;
//		$code = self::$memcache->get('Recess::Library::' . $className);
//		try {
//			if($code != '') {
//				eval($code);
//				return class_exists($className, false) || interface_exists($className);
//			} else {
//				return false;
//			}
//		} catch(Exception $e) {
//			return false;
//		}
//	}
	
	static function load($className) {
		if(!isset(self::$loaded[$className])) {
			throw new LibraryException($className . ' has not been imported.');
		}
		
		if(self::$loaded[$className]) { return; }
			
		// Search through paths to find requested file
		if(class_exists($className, false) || self::classExists(self::$classesByClass[$className][self::NAME])) {
			return;	
		} else {
			// Could not load the desired class
			$paths = '';
			foreach(self::$paths as $path) {
				$paths .= $path . '<br />';				
			}
			throw new LibraryException('Library cannot find class "' . self::$loaded[$className] . '". Searched in: <br />' . $paths);
		}
	}
	
	static function getClassName($fullName) {
		if(isset(self::$classesByFull[$fullName])) {
			return self::$classesByFull[$fullName];
		}
		
		$lastDotPosition = strrpos($fullName, self::dotSeparator);
		if($lastDotPosition === false) {
			self::$classNames[$fullName] = $fullName;
			return $fullName;
		} else {
			$className = substr($fullName, $lastDotPosition + 1);
			self::$classesByFull[$fullName] = $className;
			return $className;
		}
	}
	
	static function getFullyQualifiedClassName($className) {
		if(isset(self::$classesByClass[$className])) {
			return self::$classesByClass[$className][self::NAME];
		} else {
			return $className;
		}
	}
	
	static function findClassesIn($package) {
		$package = str_replace(self::dotSeparator,self::pathSeparator, $package);
		$classes = array();
		foreach(self::$paths as $path) {
			if(file_exists($path . self::pathSeparator . $package)) {
				$dir = dir($path . self::pathSeparator . $package);
				while(false !== ($entry = $dir->read())) {
					$extensionPosition = strpos($entry, '.class.php');
					if($extensionPosition !== false) {
						$classes[] = substr($entry, 0, $extensionPosition);
					}
				}
			}
		}
		return $classes;
	}
}

function __autoload($class) {
	try {
		Library::load($class);
	} catch (Exception $exception) {
		Diagnostics::handleException($exception);
		die();
	}
}

class Make {
	static function a($class) { return new $class; }
	static function an($class) { return new $class; }
}

register_shutdown_function(array('Library','shutdown'));

class LibraryException extends ErrorException {}

?>