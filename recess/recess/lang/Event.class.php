<?php
namespace recess\lang;
/**
 * When something important happens, let others know with an Event.
 * 
 * Events are a variation on the delegate/observable pattern. Events pass
 * their arguments to any callables who register to be called back when the
 * event is triggered.
 * 
 * =========
 *   Usage
 * =========
 * One Callback, With No Arguments
 * -------------------------------
 * $onLoad = new Event();
 * $onLoad->call(function() { echo 'Event triggered!'; });
 * echo 'Calling onLoad...';
 * $onLoad();
 * // Output: Calling onLoad... Event triggered!
 * 
 * Many Callbacks
 * --------------
 * $onLoad = new Event();
 * $onLoad->call(function() { echo 'First callback. '; })
 *        ->call(function() { echo 'Second callback.'; });
 * $onLoad();
 * // Output: First callback. Second callback.
 * // Note: Though callables are called FIFO, this is not a behavior that
 * //       should be relied upon.
 * 
 * Using Arguments
 * ---------------
 * $onSavePerson = new Event();
 * $onSavePerson->call(function($person) { echo 'Saving '.$person->name.'!'; });
 * $aPerson = new Person('Kris');
 * $onSavePerson($aPerson);
 * // Output: Saving Kris!
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @since Recess 5.3
 * @copyright RecessFramework.org 2009
 * @license MIT
 */
class Event {
	
	protected $callbacks = array();
	
	/**
	 * Register a callback with an event.
	 * 
	 * @param $callable Callable to be called when event occurs.
	 * @return Event
	 */
	function call($callable) {
		$this->callbacks[] = $callable;
		return $this;
	}
	
	/**
	 * Return the array of registered callbacks.
	 * 
	 * @return array of callables
	 */
	function callbacks() {
		return $this->callbacks;
	}
	
	/**
	 * Call each callback with arguments passed to __invoke 
	 */
	function __invoke() {
		if(!empty($this->callbacks)) {
			$args = func_get_args();
			$count = count($args);
			switch($count) {
				case 0: 
					foreach($this->callbacks as $closure) {$closure();} break;
				case 1: 
					foreach($this->callbacks as $closure) {$closure($args[0]);} break;
				case 2: 
					foreach($this->callbacks as $closure) {$closure($args[0], $args[1]);} break;
				case 3: 
					foreach($this->callbacks as $closure) {$closure($args[0], $args[1], $args[2]);} break;
				case 4: 
					foreach($this->callbacks as $closure) {$closure($args[0], $args[1], $args[2], $args[3]);} break;
				case 5: 
					foreach($this->callbacks as $closure) {$closure($args[0], $args[1], $args[2], $args[3], $args[4]);} break;
				default:
					foreach($this->callbacks as $closure) {
						call_user_func_array($closure, $args);
					}
			}
		}
	}
}