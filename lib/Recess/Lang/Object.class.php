<?php
namespace recess\lang;

use recess\lang\ReflectionClass;

// TODO: Compilation Step
// use recess\cache\Cache; 

/**
 * Object is the base class for extensible classes in the Recess.
 * Object introduces a standard mechanism for building a class
 * descriptor through reflection and the realization of Annotations.
 * Object also introduces the ability to attach methods to a class
 * at run-time.
 * 
 * Sub-classes of Object can introduce extensibility points 
 * with 'wrappable' methods. A wrappable method can be dynamically 'wrapped' 
 * by other methods which are called prior to or after the wrapped method.
 * 
 * Wrappable methods can be declared using a Wrappable annotation on the 
 * method being wrapped. The annotation takes a single parameter, which is
 * the desired name of the wrapped method. By convention the native PHP method
 * being wrapped is prefixed with 'wrapped', i.e.:
 *  class Foobar {
 *    /** !Wrappable foo * /
 *    function wrappedFoo() { ... }
 *  }
 *  $obj->foo();
 * 
 * Example usage of wrappable methods and a hypothetical "EchoWrapper" which
 * wraps a method by echo'ing strings before and after. 
 * 
 *   class Model extends Object {
 *     /** !Wrappable insert * /
 *     function wrappedInsert() { echo "Wrapped (insert)"; }
 *   }
 * 
 *   /** !EchoWrapper insert, Before: "Hello", After: "World" * /
 *   class Person extends Model {}
 * 
 *   $person = new Person();
 *   $person->insert();
 *   
 *   // Output:
 *   Hello
 *   Wrapped (insert)
 *   World
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
abstract class Object {
		
	protected static $descriptors = array();
	
	/**
	 * Attach a callable to a class. The result of this static method is the ability to
	 * call a 'method' named $alias which delegates that method call to $callable where 
	 * the first object is a reference to the object it was called on ($self).
	 *
	 * @param string $alias
	 * @param callable $callable: (Object,...) -> Any
	 * @return callable $callable Returns the callable for further chaining.
	 */
	static function attach($alias, $callable) {
		return static::getClassDescriptor()->attach($alias, $callable);
	}
		
	/**
	 * Returns an attached method with name $alias.
	 * 
	 * @param $alias
	 * @return callable
	 */
	static function attached($alias) {
		return static::getClassDescriptor()->attached($alias);
	}
	
	/**
	 * Dynamic dispatch of function calls to attached methods.
	 *
	 * @param string $name
	 * @param array $arguments
	 * @return variant
	 */
	final function __call($name, $arguments) {		
		$attachedMethod = static::getClassDescriptor($this)->getAttachedMethod($name);
		if($attachedMethod !== false) {
			array_unshift($arguments, $this);
			return call_user_func_array($attachedMethod->callable, $arguments);
		} else {
			throw new \Exception('"' . get_class($this) . '" class does not contain a method or an attached method named "' . $name . '".');
		}
	}
	
	// const RECESS_CLASS_KEY_PREFIX = 'Object::desc::';

	/**
	 * Return the ObjectInfo for provided Object instance.
	 *
	 * @param variant $classNameOrInstance - String Class Name or Instance of Recess Class
	 * @return recess\lang\ClassDescriptor
	 */
	final static function getClassDescriptor($classNameOrInstance = false) {
		if($classNameOrInstance == false) {
			$classNameOrInstance = get_called_class();
		}
		
		if(is_string($classNameOrInstance)) {
			$class = $classNameOrInstance;
		} else {
			$class = get_class($classNameOrInstance);
		}
		
		if(!isset(self::$descriptors[$class])) {
			// $cache_key = self::RECESS_CLASS_KEY_PREFIX . $class;
			$descriptor = false; // Cache::get($cache_key);
			
			if($descriptor === false) {
				if(is_subclass_of($class,'recess\lang\Object')) {
					$descriptor = $class::buildClassDescriptor();
					// Cache::set($cache_key, $descriptor);
					self::$descriptors[$class] = $descriptor;
				} else {
					throw new \Exception('Class descriptors only exist on classes derived from recess\lang\Object. Class of type "' . $class . '" given.');
				}
			} /* else {
				self::$descriptors[$class] = $descriptor;
			} */
		}
		
		return self::$descriptors[$class];
	}
	
	/**
	 * Retrieve an array of the attached methods for a particular class.
	 *
	 * @param variant $classNameOrInstance - String class name or instance of a Recess Class
	 * @return array
	 */
	final static function getAttachedMethods($classname = false) {
		return static::getClassDescriptor($classname ?: get_called_class())->getAttachedMethods();
	}	
	
	/**
	 * Initialize a class' descriptor. Override to return a subclass specific descriptor.
	 * A subclass's descriptor may need to initialize certain properties. For example
	 * Model's descriptor has properties initialized for table, primary key, etc. The controller
	 * descriptor has a routes array initialized as empty.
	 * 
	 * @param $class string Name of class whose descriptor is being initialized.
	 * @return ClassDescriptor
	 */
	protected static function initClassDescriptor() {	return new ClassDescriptor(); }
		
	/**
	 * Prior to expanding the annotations for a class method this hook is called to give
	 * a subclass an opportunity to manipulate its descriptor. For example Controller
	 * uses this in able to create default routes for methods which do not have explicit
	 * Route annotations.
	 * 
	 * @param $class string Name of class whose descriptor is being initialized.
	 * @param $method ReflectionMethod
	 * @param $descriptor ClassDescriptor
	 * @param $annotations Array of annotations found on method.
	 * @return ClassDescriptor
	 */
	protected static function shapeDescriptorWithMethod($method, $descriptor, $annotations) { return $descriptor; }
	
	/**
	 * Prior to expanding the annotations for a class property this hook is called to give
	 * a subclass an opportunity to manipulate its class descriptor. For example Model
	 * uses this to initialize the datastructure for a Property before a Column annotation
	 * applies metadata. 
	 * 
	 * @param $class string Name of class whose descriptor is being initialized.
	 * @param $property ReflectionProperty
	 * @param $descriptor ClassDescriptor
	 * @param $annotations Array of annotations found on method.
	 * @return ClassDescriptor
	 */
	protected static function shapeDescriptorWithProperty($property, $descriptor, $annotations) { return $descriptor; }

	/**
	 * After all methods and properties of a class have been visited and annotations expanded
	 * this hook provides a sub-class a final opportunity to do post-processing and sanitization.
	 * For example, Model uses this hook to ensure consistency between model's descriptor
	 * and the actual database's columns.
	 * 
	 * @param $class
	 * @param $descriptor
	 * @return ClassDescriptor
	 */
	protected static function finalClassDescriptor($descriptor) { return $descriptor; }
	
	/**
	 * Builds a class' metadata structure (Class Descriptor through reflection 
	 * and expansion of annotations. Hooks are provided in a Strategy Pattern-like 
	 * fashion to allow subclasses to influence various points in the pipeline of 
	 * building a class descriptor (initialization, discovery of method, discovery of
	 * property, finalization). 
	 * 
	 * @param $class Name of class whose descriptor is being built.
	 * @return ClassDescriptor
	 */
	protected static function buildClassDescriptor() {
		$class = get_called_class();
		$descriptor = static::initClassDescriptor();

		try {
			$reflection = new ReflectionClass($class);
		} catch(\ReflectionException $e) {
			throw new \Exception('Class "' . $class . '" has not been declared.');
		}
		
		foreach ($reflection->getAnnotations() as $annotation) {
			$annotation->expandAnnotation($class, $reflection, $descriptor);
		}
		
		foreach($reflection->getMethods(false) as $method) {
			$annotations = $method->getAnnotations();
			$descriptor = static::shapeDescriptorWithMethod($method, $descriptor, $annotations);
			foreach($annotations as $annotation) {
				$annotation->expandAnnotation($class, $method, $descriptor);
			}
		}
		
		foreach($reflection->getProperties(false) as $property) {
			$annotations = $property->getAnnotations();
			$descriptor = static::shapeDescriptorWithProperty($property, $descriptor, $annotations);
			foreach($annotations as $annotation) {
				$annotation->expandAnnotation($class, $property, $descriptor);
			}
		}
		
		$descriptor = static::finalClassDescriptor($descriptor);
		
		return $descriptor;
	}
	
	/**
	 * Clears a class' descriptor. So far there have only been 
	 * two needs for this:
	 *  1. For testing purposes and 
	 *  2. When some cached external dependency changes 
	 *  	(i.e. after a database table has been created a model's 
	 *  	descriptor should be cleared for the given run)
	 */
	public static function clearClassDescriptor() {
		$class = get_called_class();
		if(isset(self::$descriptors[$class])) {
			unset(self::$descriptors[$class]);
		}
	}
	
}