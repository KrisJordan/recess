<?php
require_once('recess/diagnostics/Diagnostics.class.php');
require_once('recess/cache/Cache.class.php');

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
	static private $paths = array();
	static private $classNames = array('Library' => 'Library');
	static private $loaded = array('Libary' => true);
	static public $memcache = false;
	static private $use_memcache = false;
	
	const dotSeparator = '.';
	const pathSeparator = '/';
	
	static function init() {
		if(self::$use_memcache && class_exists('Memcache')) {
			self::$memcache = new Memcache();
			self::$memcache->connect('localhost', 11211);
		}
	}
	
	static function addClassPath($newPath) { 
		if(!in_array($newPath, self::$paths)) {
			self::$paths[] = $newPath;
		}
	}
	
	static function import($fullyQualifiedClassName, $forceLoad = false) {
		$className = self::getClassName($fullyQualifiedClassName);
	
		if(!isset(self::$loaded[$className])) {
			self::$loaded[$className] = $fullyQualifiedClassName;
		}
		
		if($forceLoad) {
			self::load($className);
		}
	}
	
	static function classExists($fullyQualifiedClassName) {
		$file = str_replace(self::dotSeparator,self::pathSeparator, $fullyQualifiedClassName) . '.class.php';
		$className = self::getClassName($fullyQualifiedClassName);
		foreach(self::$paths as $path) {
			if(file_exists($path . $file)) {
				if(!self::$memcache) {
					include_once($path . $file);
				} else {
					$file_contents = php_strip_whitespace($path . $file);
					$file_contents = str_replace('<?php', '', $file_contents);
					$file_contents = str_replace('?>', '', $file_contents);
					if(self::$memcache) {
						self::$memcache->set('Recess::Library::' . $className, $file_contents, false, 600) or die ("Failed to save data at the server");
					}
					eval($file_contents);
					unset($file_contents);
				}
				self::$loaded[$className] = true;
				return true;
			}
		}
		return false;
	}
	
	static function loadViaMemcache($className) {
		if(class_exists($className) || interface_exists($className)) return true;
		$code = self::$memcache->get('Recess::Library::' . $className);
		try {
			if($code != '') {
				eval($code);
				return class_exists($className, false) || interface_exists($className);
			} else {
				return false;
			}
		} catch(Exception $e) {
			return false;
		}
	}
	
	static function load($className) {
		if(!isset(self::$loaded[$className])) {
			throw new LibraryException($className . ' has not been imported.');
		}
		
		$fullyQualifiedClassName = self::$loaded[$className];
		
		if($fullyQualifiedClassName === true)
			return; // Class file has already been loaded, short circuit
		
		// try loading via memcache
		if(self::$memcache) {
			if(self::loadViaMemcache($className)) {
				self::$loaded[$className] = true;
				return;
			}
		}
			
		// Search through paths to find requested file
		if(self::classExists($fullyQualifiedClassName)) {
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
	
	static function getClassName($fullyQualifiedClassName) {
		if(isset(self::$classNames[$fullyQualifiedClassName])) {
			return self::$classNames[$fullyQualifiedClassName];
		}
		
		$lastDotPosition = strrpos($fullyQualifiedClassName, self::dotSeparator);
		if($lastDotPosition === false) {
			self::$classNames[$fullyQualifiedClassName] = $fullyQualifiedClassName;
			return $fullyQualifiedClassName;
		} else {
			$className = substr($fullyQualifiedClassName, $lastDotPosition + 1);
			self::$classNames[$fullyQualifiedClassName] = $className;
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
	static function a($class) {
		return new $class;
	}
}

Library::init();

class LibraryException extends ErrorException {}

?>