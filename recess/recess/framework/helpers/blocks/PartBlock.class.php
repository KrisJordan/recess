<?php
Library::import('recess.framework.helpers.blocks.Block');
Library::import('recess.framework.helpers.Buffer');
Library::import('recess.framework.helpers.Part');
Library::import('recess.framework.helpers.exceptions.InputDoesNotExistException');

/**
 * PartBlock is an object wrapper for Part templates. Its design is
 * inspired by curried lambdas. When a PartBlock is instantiated arguments
 * can be curried into the instance. Then, when the draw method is called
 * the remaining arguments can be passed. Ex:
 * 
 * $part = new PartBlock('mypart');
 * $part->draw('foo');
 * /// Equivalent to:
 * $part = new PartBlock('mypart', 'foo');
 * $part->draw();
 * 
 * There is also a mechanism for assigning values to inputs out-of-order 
 * with jQuery style property assignment:
 * 
 * $partBlock->inputName('value')->inputName2(10)->draw();
 * 
 * The purpose of PartBlock is to enable the state of a part to be 
 * passed around and manipulated by different entities before finally
 * being drawn.
 * 
 * @author Kris Jordan
 */
class PartBlock extends Block {
	protected $partPath = '';
	protected $args = array();
	protected $curriedArgs = 0;
	
	/**
	 * Instantiate a new PartBlock by passing the name of the Part template
	 * as the first argument followed by any other arguments to curry into
	 * the instance in the order defined by the part.
	 */
	function __construct() {
		$args = func_get_args();
		
		if(!empty($args)) {
			if(count($args) == 1) {
				if(is_array($args[0])) {
					$args = $args[0];
				} else {
					$args = array($args[0]);
				}
			}
		} else {	
			throw new RecessFrameworkException('PartListBlock are required to be constructed with the name of the part as the first argument.', 1);
		}

		$this->partPath = array_shift($args);
		$this->curry($args);
	}	
	
	/**
	 * Draw may or may not take arguments depending on the inputs of the
	 * wrapped part and the arguments that were curried in the construction
	 * of the PartBlock. The Part will throw a MissingRequiredInputException
	 * if a required input has not been satisfied.
	 * 
	 * @see recess/recess/recess/framework/helpers/blocks/Block#draw()
	 */
	public function draw() {
		$args = func_get_args();
		$clone = clone $this;
		$clone->curry($args);
		try {
			Part::drawArray($clone->partPath, $clone->args);
			return true;
		} catch(MissingRequiredInputException $e) {
			throw new MissingRequiredDrawArgumentsException($e->getMessage(), 1);
		}
	}
	
	/**
	 * Converts the PartBlock to a string based on inputs available. This will 
	 * only succeed if all required inputs have been satisfied by currying or 
	 * out-of-order assignment. If not, throws 'MissingRequiredDrawArgumentsException'.
	 * 
	 * @see recess/recess/recess/framework/helpers/blocks/Block#__toString()
	 */
	public function __toString() {
		Buffer::to($returnsBlock);
		try {
			$this->draw();
		} catch(MissingRequiredDrawArgumentsException $e) {
			die($e->getMessage());
		} catch(Exception $e) {
			die($e->getMessage());
		}
		Buffer::end();
		return (string)$returnsBlock;
	}
	
	public function get($input) {
		if(!isset($this->args[$input])) {
			$inputs = Part::getInputs($this->partPath);
			if(isset($inputs[$input])) {
				if(isset($inputs[$input]['default'])) {
					eval('$this->args[$input] = ' . $inputs[$input]['default'] . ';');
				} else {
					return null;
				}
			} else {
				throw new InputDoesNotExistException("Part '$this->partPath' does not have a '$input' input.", 1);
			}
		}
		return $this->args[$input];
	}
	
	public function set($name, $value) {
		try {
			$this->assign($name, $value);
		} catch (InputDoesNotExistException $e) {
			
		} catch (InputTypeCheckException $e) {
			throw new InputTypeCheckException($e->getMessage(), 1);	
		}
		return $this;
	}
	
	/**
	 * protected helper method for currying arguments into an instance.
	 * @param array
	 */
	protected function curry($args) {
		// pair any args with their input names
		if(is_array($args) && !empty($args)) {
			$inputs = Part::getInputs($this->partPath);
			$param = 0;
			$arg = 0;
			$argCount = count($args);
			foreach($inputs as $input => $attributes) {
				if($arg >= $argCount) {
					break;
				}
				if($param >= $this->curriedArgs) {
					try {
						$this->assign($input, $args[$arg++]);
					} catch(InputTypeCheckException $e) {
						throw new InputTypeCheckException($e->getMessage(), 2);
					}				
				}
				++$param;
			}
			$this->curriedArgs = $arg;
		}
	}
	
	/**
	 * Assign a value to a property of the PartBlock. This is an internal
	 * helper method. This method will throw an InputTypeCheckException if
	 * the assigned value does not match the expected value of a template.
	 * 
	 * @param string $property input name
	 * @param varies $value The value to be assigned.
	 */
	protected function assign($property, $value) {
		$inputs = Part::getInputs($this->partPath);
		if(isset($inputs[$property])) {
			if(Part::typeCheck($value, $inputs[$property]['type'])) {
				$this->args[$property] = $value;
			} else {
				$expected = $inputs[$property]['type'];
				$passed = gettype($value);
				if($passed === 'object') {
					$passed = get_class($value);
				}
				throw new InputTypeCheckException("Part input type mismatch '$property' expects '$expected' passed '$passed'.");
			}
		} else {
			throw new InputDoesNotExistException("Part '$this->partPath' does not have a '$property' input.", 2); 
		}
	}
}
?>