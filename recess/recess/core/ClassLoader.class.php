<?php
namespace recess\core;

require __DIR__.'/Event.class.php';
require __DIR__.'/Candy.class.php';

/**
 * ClassLoader is a simple autoloader for including class files. Class files
 * end with a '.class.php' extension and classes must share the same name as
 * their containing class file. ClassLoader can be used in conjunction with 
 * the SPL autoloader chain. Its load function can be candied and wrapped 
 * with wrapLoad(). After successfully loading a class it will trigger the 
 * onLoad event.
 * 
 * Usage:
 * spl_autoload_register(array('recess\core\ClassLoader','load'));
 * use recess\core\ClassLoader;
 * // Register a call back with the onLoad Event
 * ClassLoader::onLoad()->call(function($class) { echo "$class loaded! "; });
 * // Wrap the candied loader
 * ClassLoader::wrapLoad(
 *   function($load, $class) {
 *     echo "Before load $class!";
 *     $load($class);
 *     echo "After load $class!";
 *   }
 * );
 * // Load a class explicitely
 * ClassLoader::load('a\Class');
 * // Output: Before load A\Class! A\Class loaded! After load A\Class!
 * // Load a class implicitly
 * use some\Class;
 * $someClass = new Class;
 * // Output: Before load some\Class! some\Class loaded! After load some\Class!
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @since Recess 5.3
 * @copyright RecessFramework.org 2009
 * @license MIT
 */
abstract class ClassLoader {
	
	/**
	 * @var string
	 */
	public static $extension = '.class.php';
	
	/**
	 * @var recess\core\Event
	 */
	private static $onLoad = null;
	
	/**
	 * @var recess\core\Candy or \Closure
	 */
	private static $loader = null;
	
	/**
	 * Returns a reference to the onLoad Event for interested parties
	 * to register callbacks with.
	 * @return recess\core\Event
	 */
	static public function onLoad() {
		if(self::$onLoad === null) {
			self::$onLoad = new Event();
		}
		return self::$onLoad;
	}
	
	/**
	 * Load a class by passing a fully qualified classname.
	 * @param $class string fully qualified classname
	 * @return bool
	 */
	static public function load($class) {
		if(self::$loader === null) {
			$loader = self::loader();
		} else {
			$loader = self::$loader;
		}
		return $loader($class);
	}
	
	/**
	 * Load is candied and can be wrapped by passing a wrapper to this method.
	 * The wrapper should have two parameters: $load and $class. Usage:
	 * 
	 * ClassLoader::wrapLoad(function($load,$class) { echo "loading $class"; $load($class); });
	 * 
	 * @param $wrapper
	 * @return recess\core\Candy
	 */
	static public function wrapLoad($wrapper) {
		if(self::$loader === null) {
			self::$loader = new Candy(self::loader());
		}
		if(!self::$loader instanceof Candy) {
			self::$loader = new Candy(self::$loader);
		}
		return self::$loader->wrap($wrapper);
	}
	
	/**
	 * Returns a closure for loading class files.
	 * 
	 * @return \Closure
	 */
	static private function loader() {
		if(self::$loader === null) {
			$onLoad = self::onLoad();
			$extension = self::$extension;
			self::$loader = function($class) use (&$onLoad, &$extension) {
				if(!class_exists($class)) {
					$classFile = str_replace('\\','/',$class).$extension;
					include $classFile;
					$onLoad($class);
				}
				return true;
			};
		}		
		return self::$loader;
	}
}