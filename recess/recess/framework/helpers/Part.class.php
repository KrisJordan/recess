<?php
Library::import('recess.framework.helpers.AssertiveTemplate');
Library::import('recess.framework.helpers.blocks.PartBlock');
Library::import('recess.framework.helpers.exceptions.MissingRequiredDrawArgumentsException');

/**
 * A Part is a template that defines specific inputs and has similarities 
 * to 'partials' of other frameworks. These inputs are defined sequentially
 * so that parts can be drawn with a list of arguments just like a function
 * call. Parts have a Block counterpart: PartBlock which can be instantiated
 * using the static 'block' method.
 * 
 * Part templates require the extension: '.part.php'
 * 
 * @author Kris Jordan
 */
class Part extends AssertiveTemplate {
	protected static $app;
	
	/**
	 * Returns a multi-dimensional array that describes the inputs
	 * of a part. Data available: 
	 *   array[$inputName]['required'] = boolean
	 *   array[$inputName]['type'] = string type representation
	 *   
	 * @param string Part name relative to AssertiveTemplate's paths.
	 * @param string Always use 'Part' here.
	 * @returns array Representation of required inputs.
	 */
	public static function getInputs($part, $class = 'Part') {
		return parent::getInputs($part . '.part.php', $class);
	}
	
	/**
	 * Send a part directly to output with the provided arguments. The first
	 * argument is the filename of the part relative to paths registered with
	 * AssertiveTemplate minus the '.part.php' extension. Subsequent arguments
	 * depend on the parts themselves.
	 * 
	 * @param string The filename of part relative to AssertiveTemplate's paths minus '.part.php'
	 * @param Depends on the part's inputs.
	 * @return boolean Returns true if successful. Throws if unsuccessful.
	 */
	public static function draw() {
		$args = func_get_args();
		try {	
			if(!empty($args)) {
				$partPath = array_shift($args);
				
				$argCount = count($args);
				if($argCount > 0) {
					$inputs = self::getInputs($partPath);
					$inputKeys = array_keys($inputs);
					$inputCount = count($inputKeys);
					if($inputCount > $argCount) {
						$inputKeys = array_slice($inputKeys, 0, $argCount);
					} else {
						$args = array_slice($args, 0, $inputCount);
					}
					if(!empty($inputKeys)) {
						$args = array_combine($inputKeys, $args);
					}
				}
				
				self::drawArray($partPath, $args);
			}
		} catch(MissingRequiredInputException $e) {
			throw new MissingRequiredDrawArgumentsException($e->getMessage(), 1);
		}
		
		return true;
	}
	
	/**
	 * Factory method to produce PartListBlock, a Block equivalent to Part. PartBlock
	 * instances store the arguments they've been instantiated with so that when
	 * their draw method is called only the remaining arguments (if any) must be
	 * passed. Ex:
	 * 
	 * $block = Block::part('a-part', 'one');
	 * $block->draw();
	 * 
	 * Equivalent to:
	 * $block = Block::part('a-part');
	 * $block->draw('one')
	 * 
	 * @see BlockPart for more information on its capabilities.
	 * @param string The name of the part template.
	 * @param varies Based on part.
	 * @return PartBlock Returns a block that retains the arguments passed in.
	 */
	public static function block() {
		$args = func_get_args();
		return new PartBlock($args);
	}
	
	/**
	 * Draw a part by passing a key/value array where the keys match the
	 * part's input variable names.
	 * 
	 * @param string The name of the part template.
	 * @param array Key/values according to part's input(s).
	 * @return boolean True if successful, throws exception if unsuccessful.
	 */
	public static function drawArray($partPath = '', $args = array()) {
		if(!is_array($args)) {
			throw new RecessFrameworkException("Part::drawArray must be called with an array.", 1);
		}
		if($partPath === '') {
			throw new RecessFrameworkException("First parameter 'partPath' must not be empty.", 1);
		}
		
		try {
			$inputs = self::getInputs($partPath, 'Part');
		} catch(Exception $e) { 
			throw new RecessFrameworkException("Could not find Part: $partPath", 1);
		}
		
		$part = $partPath;
		$part .= '.part.php';
		
		// What if drawArray is always passed key=>value pairs?
		$context = array_intersect_key($args, $inputs);
		
		self::includeTemplate($part, $context);
		
		return true;
	}
}
?>