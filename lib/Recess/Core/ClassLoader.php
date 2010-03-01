<?php
namespace Recess\Core; /** @addtogroup Core *//** @{ */

if(!defined('NAMESPACE_SEPARATOR')) {
	define('NAMESPACE_SEPARATOR','\\');
}

require_once __DIR__.'/ICallable.php';
require_once __DIR__.'/Event.php';
require_once __DIR__.'/Callable.php';
require_once __DIR__.'/Wrappable.php';

/**
 * An SPL class loader for automatically including class and interface files. 
 * 
 * ClassLoader implements http://groups.google.com/group/php-standards/web/psr-0-final-proposal
 * for painless interoperability with other PHP libraries.
 * 
 * Class files end with a '.php' extension and classes must share the same name as
 * their containing class file. 
 * 
 * ClassLoader can be used in conjunction with the SPL autoloader chain. 
 * Its load function can be wrapped with wrapLoad(). After successfully loading 
 * a class or interface it will trigger the onLoad event.
 * 
 * Example usage:
 * 
 * @code
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
 * @endcode
 * 
 * @author Kris Jordan <http://www.krisjordan.com>
 * @author Copyright &copy; RecessFramework.org 2008-2010 (MIT License)
 * @since Recess PHP Framework 5.3
 */
abstract class ClassLoader {
/** @} */
	
	/** @var string */
	private static $extension = '.php';
	
	/** @var Recess\Core\Event */
	private static $onLoad = null;
	
	/** @var Recess\Core\Wrappable or \Closure */
	private static $loader = null;
	
	/**
	 * Load a class by passing a fully qualified classname.
	 * @param $class string fully qualified classname
	 * @return boolean
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
	 * Returns a reference to the onLoad Event that callbacks can be registered on.
	 * 
	 * @code
	 * ClassLoader::onLoad()->callback(function($class){echo "$class loaded!";});
	 * @endcode
	 * 
	 * @return Event
	 */
	static public function onLoad() {
		if(self::$onLoad === null) {
			self::$onLoad = new Event();
		}
		return self::$onLoad;
	}
	
	/**
	 * Load is wrappable by passing a wrapper to this method.
	 * The wrapper should have two parameters: $load and $class. Usage:
	 * 
	 * @code
	 * ClassLoader::wrapLoad(function($load,$class) { echo "loading $class"; $load($class); });
	 * @endcode
	 * 
	 * @param $wrapper
	 * @return recess\core\Wrappable
	 */
	static public function wrapLoad($wrapper) {
		if(self::$loader === null) {
			self::$loader = new Wrappable(self::loader());
		}
		if(!self::$loader instanceof Wrappable) {
			self::$loader = new Wrappable(self::$loader);
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
			self::$loader = function($fullyQualifiedClass) use (&$onLoad, &$extension) {
				if(class_exists($fullyQualifiedClass, false)
					|| interface_exists($fullyQualifiedClass, false)) { return true; }

				$class = $fullyQualifiedClass;
				$namespace = '';
				
				$lastNamespaceSeparator = strripos($fullyQualifiedClass, NAMESPACE_SEPARATOR);
				if($lastNamespaceSeparator !== false) {
					$namespace = substr($fullyQualifiedClass, 0, $lastNamespaceSeparator + 1);
                	$class = substr($fullyQualifiedClass, $lastNamespaceSeparator + 1);
				}
				
				$classFile = str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $namespace) .
							 str_replace('_', DIRECTORY_SEPARATOR, $class) .
							 $extension;
				
				$paths = explode(PATH_SEPARATOR, get_include_path());
				$found = false;
				foreach($paths as $path) {
					$classFilePath = $path.DIRECTORY_SEPARATOR.$classFile;
					if(file_exists($classFilePath) && is_readable($classFilePath)) {
						$found = true;
						require_once $classFilePath;
					}
				}
				if($found === false) return false;
				
				$onLoad($fullyQualifiedClass);
				if(class_exists($fullyQualifiedClass, false) 
				   || interface_exists($fullyQualifiedClass, false)) {
					return true;
				} else {
					throw new \Exception("'$classFile' does not contain a definition of $class.");
				}
			};
		}
		return self::$loader;
	}
	
	/**
	 * Reset internal state, only useful for unit testing.
	 */
	static public function reset() {
		self::$onLoad = null;
		self::$loader = null;
	}
}