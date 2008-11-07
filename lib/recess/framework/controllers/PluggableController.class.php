<?php

Library::import('recess.interfaces.IController');
Library::import('recess.interfaces.IPlugin');

class PluggableController implements IController {
	
	protected $controller;
	protected $plugins = array();
	
	public function __construct(IController $controller) {
		$this->controller = $controller;
	}
	
	public function addPlugin(IPlugin $plugin) {
		$this->plugins[] = $plugin;
	}
	
	public function serve(Request $request) {
		foreach($this->plugins as $plugin) {
			$result = $plugin->serve($request);
			if(is_null($result)) continue;
			else if ($result instanceof Request) $request = $result;
			else if ($result instanceof Response) return $result; // short-circuit a Response
			else throw new RecessException('Invalid return type (' . gettype($result) . ') of a controller wrapper! Expected: null, Request, Response.', get_defined_vars());
		}
		
		if(!is_a($this->controller, 'IController')) { 
			throw new RecessException('Provided controller does not implement IController interface.', array('controller' => $this->controller));
		} else {
			return $this->controller->serve($request);
		}
		
	}
	
}

?>