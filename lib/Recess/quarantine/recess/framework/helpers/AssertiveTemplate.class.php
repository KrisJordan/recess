<?php
Library::import('recess.cache.Cache');
Library::import('recess.lang.PathFinder');
Library::import('recess.framework.helpers.exceptions.MissingRequiredInputException');
Library::import('recess.framework.helpers.exceptions.InputTypeCheckException');

/**
 * AssertiveTemplate is a helper class that provides support for
 * 'Assertive Templates' or templates that assert their inputs. Typically
 * you will use a subclass of AssertiveTemplate, rather than the base
 * class itself.
 * 
 * @author Kris Jordan
 */
abstract class AssertiveTemplate {
	
	/**
	 * Used to locate AssertiveTemplates 
	 * @var Paths
	 */
	private static $paths = false;
	
	/**
	 * Initialize the AssertiveTemplate helper class by registering
	 * the application's views directory as a path.
	 * 
	 * @param AbstractView
	 */
	public static function init(AbstractView $view = null) {
		self::setPathFinder(Application::active()->viewPathFinder());
	}
	
	/**
	 * Add a directory to be checked for the existance of AssertiveTemplates.
	 * Paths are checked in the reverse order of their being added so
	 * that the most specific paths are checked first.
	 * @param string $path
	 */
	public static function addPath($path) {
		if(!self::$paths instanceof PathFinder) {
			// To-do: Cache Paths
			self::$paths = new PathFinder();
		}
		self::$paths->addPath($path);
	}
	
	/**
	 * Set the PathFinder to use when looking for Assertive Templates
	 * @param PathFinder $pathFinder
	 */
	public static function setPathFinder(PathFinder $pathFinder) {
		self::$paths = $pathFinder;
	}
	
	protected static $loaded = array();
	
	/**
	 * Include a PHP file with a context and return the context that results
	 * after the template has been executed.
	 * 
	 * @param string The PHP file to include relative to registered paths.
	 * @param array An associative array whose keys will become variables in the template.
	 * @return array The context after execution of the template as a key/value array.
	 */
	public static function includeTemplate($__assertive_template__, $context) {
		$__assertive_template__ = self::$paths->find($__assertive_template__);
		if($__assertive_template__ === false) {
			throw new Exception('Could not locate AssertiveTemplate: ' . $__assertive_template__);
		}
		// Unset 'context' if it isn't a key in $context
		if(isset($context['context'])) {
			extract($context);
		} else {
			extract($context);
			unset($context);
		}
		include $__assertive_template__;
		return get_defined_vars();
	}
	
	static $types = array('string','int','bool','float','array');
	
	/**
	 * input is used by the assertive templates themselves to assert the
	 * variable name and type of an expected input, as well as a default
	 * value for optional inputs. If the input is required and is missing
	 * this will throw a MissingRequiredInputException. If the input type
	 * does not match the expected type this will throw an 
	 * InputTypeCheckException.
	 * 
	 * @param varies based on the type string passed at argument 2.
	 * @param string The type $input is expected to be.
	 * @param varies Default value for optional arguments.
	 * 
	 * @return Returns the $input, if $input was null and optional returns $default.
	 */
	public static function input(&$input, $type, $default = null) {
		if($input === NULL && $default !== null) {
			$input = $default;
		} else {
			if($input === null) {
				/** Hacky for better debuging experience. Analyze stack to find name of required input missing. */
				$stack = debug_backtrace();
				if(isset($stack[0])) {
					$script = explode("\n", file_get_contents($stack[0]['file']));
					$lineNumber = $stack[0]['line'] - 1;
					$line = $script[$lineNumber];
					preg_match('/\s*(\S*?)::input\(/', $line, $matches);
					if(isset($matches[1])) {
						preg_match(self::getInputRegex($matches[1]), $line, $inputMatches);
						if(isset($inputMatches[1])) {
							throw new MissingRequiredInputException('Missing input "'.$inputMatches[1].'" of type "'.$type.'" in: '.$stack[0]['file'], 1);
						}
					}
				}
				throw new MissingRequiredInputException('Missing required ' . $type . ' input in :' , 1);
			}
		}
		
		if(!self::typeCheck($input, $type)) {
			$passed = gettype($input);
			if($passed === 'object') {
				$passed = get_class($input);
			}
			throw new InputTypeCheckException("Input type mismatch, expected: '$type', actual:'$passed'.", 1);			
		}
		
		return $input;
	}
	
	/**
	 * Determines whether a provided value is of the requested type. Not
	 * quite the same as PHP's internal type checking to allow for type
	 * 'array' to be satisfied by implementations of ArrayAccess. The type
	 * argument is a string that can be either a PHP type like 'string',
	 * 'int', 'float', or a class name.
	 * 
	 * @param variable The value whose type is being checked.
	 * @param string The expected type.
	 * @return boolean True if $value is a $type. False if not.
	 */
	public static function typeCheck($value, $type) {
		if(in_array($type, self::$types)) {
			if($type === 'array') {
				if(!(is_array($value) || $value instanceof ArrayAccess)) {
					return false;
				} else {
					return true;
				}
			} else {
				$fn = 'is_' . $type;
				if(!$fn($value)) {
					return false;
				} else {
					return true;
				}
			}
		} else {
			if(!$value instanceof $type) {
				return false;
			} else {
				return true;
			}
		}
	}
	
	/**
	 * Returns a multi-dimensional array that describes the inputs
	 * of an assertive template. Data available: 
	 *   array[$inputName]['required'] = boolean
	 *   array[$inputName]['type'] = string type representation
	 *   array[$inputName]['default'] = string default value
	 *   
	 * @param string Part name relative to AssertiveTemplate's paths.
	 * @param string The class name to look for, i.e. for Part::input 'Part', Layout::input 'Layout'
	 * @returns array Representation of required inputs.
	 */
	public static function getInputs($template, $class = 'AssertiveTemplate') {
		if(!isset(self::$loaded[$template])) {
			$cacheKey = 'AssertiveTemplate::inputs::' . $template;
			if(($inputs = Cache::get($cacheKey)) !== false) {
				self::$loaded[$template] = $inputs;
			} else {
				if(self::$paths === false) {
					self::init();
				}
				$templateFile = self::$paths->find($template);
				if($templateFile === false) {
					throw new RecessFrameworkException("The file \"$template\" does not exist.", 1);
				}
				$file = file_get_contents($templateFile);
				$pattern = self::getInputRegex($class);
				preg_match_all($pattern, $file, $matches);
		
				$inputs = array();
				foreach($matches[0] as $key => $value) {
					$input = array();
					$name = $matches[1][$key];
					$input['type'] = $matches[2][$key];
					$input['required'] = !isset($matches[3][$key]) || $matches[3][$key] === '';
					if(!$input['required']) {
						$input['default'] = $matches[3][$key];
					} else {
						$input['default'] = null;
					}
					$inputs[$name] = $input;
				}

				self::$loaded[$template] = $inputs;
				Cache::set($cacheKey, $inputs);
			}
		}
		return self::$loaded[$template];
	}
	
	/**
	 * Returns the regex to extract all inputs from a file.
	 * @param string The class name to search for.
	 * @return string The regex.
	 */
	private static function getInputRegex($class) {
		$ws = '(?:\s*)';
		$openParen = '\(';
		$closeParen = '\)';
		$identifier = '[a-zA-Z_][a-zA-Z_0-9]*';
		$quote = '["\']';
		$classInput = "$class$ws::$ws" . "input$ws";
		$dollar = '\$';
		$pattern = "/$classInput$openParen$ws$dollar($identifier)$ws,$ws$quote($identifier)$quote$ws(?:,$ws(.*)|$ws)?$closeParen$ws;/";
		return $pattern;	
	}
}
?>