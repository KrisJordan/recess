<?php
namespace Recess\Core; /** @addtogroup Core *//** @{ */

/** 
 * A lightweight, functional variation on the delegate/observable pattern. 
 * Events pass their arguments to any callables registered with callback() 
 * when the Event is triggered.
 * 
 * @include examples/Recess/Core/Event.php
 * 
 * To run the example code from the command line:
 * @code php lib/Recess/docs/examples/Recess/Core/Event.php @endcode
 * 
 * @author Kris Jordan <http://www.krisjordan.com>
 * @author Copyright &copy; RecessFramework.org 2008-2010 (MIT License)
 * @since Recess PHP Framework 5.3
 */
class Event implements ICallable {
/** @} */
	
	/** Listeners registered to receive notice of the event trigger. */
	protected $callbacks = array();
	
	/**
	 * Trigger the event and call each callback with arguments passed to __invoke. 
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
	 * An alias of __invoke() called with an array of arguments.
	 * 
	 * @param array $arguments
	 * @return mixed
	 */
	function apply($arguments = array()) {
		return call_user_func_array(array($this,'__invoke'), $arguments);
	}
		
	/**
	 * An alias of __invoke().
	 * 
	 * @see Event::__invoke
	 * @return mixed
	 */
	function call() {
		return call_user_func_array(array($this,'__invoke'), func_get_args());
	}
	
	/**
	 * Register a callback with the event.
	 * 
	 * @param is_callable $callback to be called when the event is triggered.
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
	 * @return array
	 */
	function callbacks() {
		return $this->callbacks;
	}

}