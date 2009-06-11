<?php
Library::import('recess.lang.ClassDescriptor');
Library::import('recess.lang.AttachedMethod');
Library::import('recess.lang.WrappableAnnotation');
Library::import('recess.lang.BeforeAnnotation');
Library::import('recess.lang.AfterAnnotation');

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
	 * Attach a method to a class. The result of this static method is the ability to
	 * call, on any instance of $attachOnClassName, a method named $attachedMethodAlias
	 * which delegates that method call to $providerInstance's $providerMethodName.
	 *
	 * @param string $attachOnClassName
	 * @param string $attachedMethodAlias
	 * @param object $providerInstance
	 * @param string $providerMethodName
	 */
	static function attachMethod($attachOnClassName, $attachedMethodAlias, $providerInstance, $providerMethodName) {
		self::getClassDescriptor($attachOnClassName)->attachMethod($attachOnClassName, $attachedMethodAlias, $providerInstance, $providerMethodName);
	}
	
	/**
	 * Wrap a method on a class. The result of this static method is the provided IWrapper
	 * implementation will be called before and after the wrapped method.
	 * 
	 * @param string $wrapOnClassName
	 * @param string $wrappableMethodName
	 * @param IWrapper $wrapper
	 */
	static function wrapMethod($wrapOnClassName, $wrappableMethodName, IWrapper $wrapper) {
		self::getClassDescriptor($wrapOnClassName)->addWrapper($wrappableMethodName, $wrapper);
	}
	
	/**
	 * Dynamic dispatch of function calls to attached methods.
	 *
	 * @param string $name
	 * @param array $arguments
	 * @return variant
	 */
	final function __call($name, $arguments) {
		$classDescriptor = self::getClassDescriptor($this);
		
		$attachedMethod = $classDescriptor->getAttachedMethod($name);
		if($attachedMethod !== false) {
			$object = $attachedMethod->object;
			$method = $attachedMethod->method;
			array_unshift($arguments, $this);
			$reflectedMethod = new ReflectionMethod($object, $method);
			return $reflectedMethod->invokeArgs($object, $arguments);
		} else {
			throw new RecessException('"' . get_class($this) . '" class does not contain a method or an attached method named "' . $name . '".', get_defined_vars());
		}
	}
	
	const RECESS_CLASS_KEY_PREFIX = 'Object::desc::';

	/**
	 * Return the ObjectInfo for provided Object instance.
	 *
	 * @param variant $classNameOrInstance - String Class Name or Instance of Recess Class
	 * @return ClassDescriptor
	 */
	final static protected function getClassDescriptor($classNameOrInstance) {
		if($classNameOrInstance instanceof Object) {
			$class = get_class($classNameOrInstance);
			$instance = $classNameOrInstance;
		} else {
			$class = $classNameOrInstance;
			if(class_exists($class, true)) {
				$reflectionClass = new ReflectionClass($class);
				if(!$reflectionClass->isAbstract()) {	
					$instance = new $class;
				} else {
					return new ClassDescriptor();
				}
			}
		}
		
		if(!isset(self::$descriptors[$class])) {		
			$cache_key = self::RECESS_CLASS_KEY_PREFIX . $class;
			$descriptor = Cache::get($cache_key);
			
			if($descriptor === false) {				
				if($instance instanceof Object) {
					$descriptor = call_user_func(array($class, 'buildClassDescriptor'), $class);
					
					Cache::set($cache_key, $descriptor);
					self::$descriptors[$class] = $descriptor;
				} else {
					throw new RecessException('ObjectRegistry only retains information on classes derived from Object. Class of type "' . $class . '" given.', get_defined_vars());
				}
			} else {
				self::$descriptors[$class] = $descriptor;
			}
		}
		
		return self::$descriptors[$class];
	}
	
	/**
	 * Retrieve an array of the attached methods for a particular class.
	 *
	 * @param variant $classNameOrInstance - String class name or instance of a Recess Class
	 * @return array
	 */
	final static function getAttachedMethods($classNameOrInstance) {
		$descriptor = self::getClassDescriptor($classNameOrInstance);
		return $descriptor->getAttachedMethods();
	}	
	
	/**
	 * Clear the descriptors cache.
	 */
	final static function clearDescriptors() {
		self::$descriptors = array();
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
	protected static function initClassDescriptor($class) {	return new ClassDescriptor(); }
		
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
	protected static function shapeDescriptorWithMethod($class, $method, $descriptor, $annotations) { return $descriptor; }
	
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
	protected static function shapeDescriptorWithProperty($class, $property, $descriptor, $annotations) { return $descriptor; }

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
	protected static function finalClassDescriptor($class, $descriptor) { return $descriptor; }
	
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
	protected static function buildClassDescriptor($class) {
		$descriptor = call_user_func(array($class, 'initClassDescriptor'), $class);

		try {
			$reflection = new RecessReflectionClass($class);
		} catch(ReflectionException $e) {
			throw new RecessException('Class "' . $class . '" has not been declared.', get_defined_vars());
		}
		
		foreach ($reflection->getAnnotations() as $annotation) {
			$annotation->expandAnnotation($class, $reflection, $descriptor);
		}
		
		foreach($reflection->getMethods(false) as $method) {
			$annotations = $method->getAnnotations();
			$descriptor = call_user_func(array($class, 'shapeDescriptorWithMethod'), $class, $method, $descriptor, $annotations);
			foreach($annotations as $annotation) {
				$annotation->expandAnnotation($class, $method, $descriptor);
			}
		}
		
		foreach($reflection->getProperties(false) as $property) {
			$annotations = $property->getAnnotations();
			$descriptor = call_user_func(array($class, 'shapeDescriptorWithProperty'), $class, $property, $descriptor, $annotations);
			foreach($annotations as $annotation) {
				$annotation->expandAnnotation($class, $property, $descriptor);
			}
		}
		
		$descriptor = call_user_func(array($class, 'finalClassDescriptor'), $class, $descriptor);
		
		return $descriptor;
	}	
	
}

?>