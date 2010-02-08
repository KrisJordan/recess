<?php
namespace Recess\Core;
/** @addtogroup Core *//** @{ */

/** 
 * When something important happens, let others know with an Event.
 * 
 * Events are a variation on the delegate/observable pattern. Events pass
 * their arguments to any callables who register to be called back when the
 * event is triggered.
 * 
 * One Callback, With No Arguments
 * @code
 * $onLoad = new Event();
 * $onLoad->callback(function() { echo 'Event triggered!'; });
 * echo 'Calling onLoad...';
 * $onLoad();
 * // Output: Calling onLoad... Event triggered!
 * @endcode
 * 
 * Many Callbacks
 * @code
 * $onLoad = new Event();
 * $onLoad->callback(function() { echo 'First callback. '; })
 *        ->callback(function() { echo 'Second callback.'; });
 * $onLoad();
 * // Output: First callback. Second callback.
 * // Note: Though callables are called FIFO, this is not a behavior that
 * //       should be relied upon.
 * @endcode
 * 
 * Using Arguments
 * @code
 * $onSavePerson = new Event();
 * $onSavePerson->callback(function($person) { echo 'Saving '.$person->name.'!'; });
 * $aPerson = new Person('Kris');
 * $onSavePerson($aPerson);
 * // Output: Saving Kris!
 * @endcode
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @since Recess 5.3
 * @copyright RecessFramework.org 2009, 2010
 * @license MIT
 */
class Event implements ICallable {
/** @} */
	
	protected $callbacks = array();
	
	/**
	 * Register a callback with an event.
	 * 
	 * @param $callable Callable to be called when event occurs.
	 * @return Event
	 */
	function callback($callback) {
		if(!is_callable($callback)) {
			throw new \Exception("Event's constructor requires an is_callable value.");
		}
		$this->callbacks[] = $callback;
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
			foreach($this->callbacks as $callback) {
				call_user_func_array($callback, $args);
			}
		}
		return $this;
	}
	
	/**
	 * Helper method alias for __invoke() that can be chained.
	 * 
	 * @see Event::__invoke
	 * @return any
	 */
	function call() {
		return call_user_func_array(array($this,'__invoke'), func_get_args());
	}
	
	/**
	 * Call with an array of arguments rather than an argument list.
	 * 
	 * @param array $arguments
	 * @return any
	 */
	function apply($arguments = array()) {
		return call_user_func_array(array($this,'__invoke'), $arguments);
	}
}