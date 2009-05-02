<?php
require_once($_ENV['dir.recess'] . 'recess/diagnostics/Diagnostics.class.php');
require_once($_ENV['dir.recess'] . 'recess/cache/Cache.class.php');

/**
 * Library is an important low level utility in Recess used to make importing class files
 * less painful. Library is also an important level of indirection between PHP's native
 * include/require functions. Library is how Recess dynamically 'compiles' all classes
 * into a single PHP file.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 * 
 * @todo Allow framework to register packages/shortcuts? i.e.: Library::import('recess.framework.models.Model') vs. Library::import('recess','Model')
 */
class Library {
	static private $classesByClass = array();	// = array( 'Inflector' => array( 'recess.lang.Inflector', 0 );
	static private $classesByFull = array();	// 'recess.lang.Inflector'
	static private $dirtyClasses = false;
	
	static private $paths = array();
	static private $dirtyPaths = false;
	
	static private $loaded = array('Libary' => true);
	
	const dotSeparator = '.';
	const pathSeparator = '/';
	
	const CLASSES_X_CLASS_CACHE_KEY = 'Recess::*::Library::$classesByClass';
	const CLASSES_X_FULL_CACHE_KEY = 'Recess::*::Library::$classesByFull';
	const PATHS_CACHE_KEY = 'Recess::*::Library::$paths';
	const NAMED_RUNS_PATH = '';
	const PHP_EXTENSION = '.php';
	const CLASS_FILE_EXTENSION = '.class.php';
	const CLASS_FILE_EXTENSION_LENGTH = 10;
	
	const NAME = 0;
	const PATH = 1;
	
	static public $useNamedRuns = false;
	static private $namedRun;
	static private $namedRuns = array();
	static private $inNamedRunImport = false;
	
	static function beginNamedRun($name) {
		if(!self::$useNamedRuns || !isset($_ENV['dir.temp'])) return;
		
		self::$namedRun = $name;
		if(!isset(self::$namedRuns[$name])) {
			self::$namedRuns[$name] = array();
		} else {
			if(!is_array(self::$namedRuns[$name])) {
				self::$namedRuns[$name] = array();
			}
		}
		
		$namedRunFile = $_ENV['dir.temp'] . self::NAMED_RUNS_PATH . $name . self::PHP_EXTENSION;
		if(file_exists($namedRunFile)) {
			self::$inNamedRunImport = true;
			include_once($namedRunFile);
			self::$inNamedRunImport = false;
		}
	}
	
	static function namedRunMissed($class) {
		if($class != 'Smarty')
			self::$namedRuns[self::$namedRun][] = $class;
	}
	
	static function persistNamedRuns() {		
		if(!isset($_ENV['dir.temp'])) return;
		$tempDir = $_ENV['dir.temp'];
		foreach(self::$namedRuns as $namedRun => $missedClasses) {
			$namedRunDir = $_ENV['dir.temp'] . self::NAMED_RUNS_PATH;
			$namedRunFile = $namedRunDir . $namedRun . self::PHP_EXTENSION;
			
			if(!empty($missedClasses)) {
				if(file_exists($namedRunFile)) { // append to
					$file = fopen($namedRunFile,'a');
				} else {
					if(!file_exists($namedRunDir)) {
						mkdir($namedRunDir);
					}
					$file = fopen($namedRunFile,'w');
				}
				
				foreach($missedClasses as $class) {
					$classInfo = self::$classesByClass[$class];
					$fullName = $classInfo[self::NAME];
					$path = self::$paths[$classInfo[self::PATH]];
					$fileName = str_replace(self::dotSeparator,self::pathSeparator, $fullName) . self::CLASS_FILE_EXTENSION;
					$classFile = $path . $fileName;
					$code = rtrim(file_get_contents($classFile));
					if(substr($code,-2)!='?'.'>') $code .= ('?'.'>');
					fwrite($file, $code);
				}
				
				fclose($file);
			}
		}
	}
	
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

		self::persistNamedRuns();
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
		if(class_exists($class, false) || interface_exists($class, false)) return true;
		
		$pathIndex = self::$classesByClass[$class][self::PATH];
		$file = str_replace(self::dotSeparator,self::pathSeparator, $fullName) . self::CLASS_FILE_EXTENSION;
		
		if($pathIndex == -1) {
			foreach(self::$paths as $index => $path) {
				if(file_exists($path . $file)) {
					include_once($path . $file);
					if(class_exists($class, false) || interface_exists($class, false)) {
						if(isset(self::$namedRun)) { self::namedRunMissed($class); }
						self::$dirtyClasses = true;
						self::$classesByClass[$class][self::PATH] = $index;
						self::$loaded[$class] = true;
						return true;
					}
				}
			}
			return false;
		} else {
			$path = self::$paths[$pathIndex] . $file;
			include_once($path);
			self::$loaded[$class] = true;
		}
	
		if(isset(self::$namedRun)) { self::namedRunMissed($class); }
		
		return class_exists($class, false) || interface_exists($class, false);
	}
	
	static function load($className) {
		if(self::$inNamedRunImport) return;
				
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
			throw new LibraryException('Library cannot find class "' . self::$classesByClass[$className][self::NAME] . '". Searched in: <br />' . $paths);
		}
	}
	
	static function getClassName($fullName) {
		if(isset(self::$classesByFull[$fullName])) {
			return self::$classesByFull[$fullName];
		}
		
		$lastDotPosition = strrpos($fullName, self::dotSeparator);
		if($lastDotPosition === false) {
			self::$classesByFull[$fullName] = $fullName;
			return $fullName;
		} else {
			$className = substr($fullName, $lastDotPosition + 1);
			self::$classesByFull[$fullName] = $className;
			return $className;
		}
	}
	
	static function getPackage($className) {
		if(isset(self::$classesByClass[$className])) {
			return str_replace('.' . $className, '', self::$classesByClass[$className][self::NAME]);
		} else {
			return '';
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
					$extensionPosition = strpos($entry, self::CLASS_FILE_EXTENSION);
					if($extensionPosition === strlen($entry) - self::CLASS_FILE_EXTENSION_LENGTH) {
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
		die('Could not autoload ' . $class);
	}
}

class Make {
	static function a($class) { return new $class; }
	static function an($class) { return new $class; }
}

register_shutdown_function(array('Library','shutdown'));

class LibraryException extends ErrorException {}

?>