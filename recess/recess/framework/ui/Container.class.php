<?php
Library::import('recess.lang.Inflector');
Library::import('recess.framework.helpers.blocks.PartBlock');
Library::import('recess.framework.ui.Control');
Library::import('recess.framework.helpers.blocks.ArrayBlock');

// @TODO: Can we handle out of order ->set('value')->add('control') ?

class Container extends Control {

	function __construct($part = 'default', $name = '') {
		$part = 'containers/' . $part;
		PartBlock::__construct($part, $this, $name, new ArrayBlock());
	}
	
	public static function make($part, $name, $value = '', $label = false) {
		return new Container($part, $name);
	}
	
	function add(Control $child) {
		$children = $this->get('children');
		$children[$child->get('name')] = $child;
		$child->setParent($this);
		return $this;
	}
	
	function addBefore($childName, $newChild) {
		$children = $this->get('children');
		$newChildren = new ArrayBlock();
		foreach($children as $name => $child) {
			if($childName == $name) {
				$newChildren[$newChild->get('name')] = $newChild;
			}
			$newChildren[$name] = $child;
		}
		$this->set('children', $newChildren);
		$newChild->setParent($this);
		return $this;
	}
	
	function addAfter($childName, $newChild) {
		$children = $this->get('children');
		$newChildren = new ArrayBlock();
		foreach($children as $name => $child) {
			$newChildren[$name] = $child;
			if($childName == $name) {
				$newChildren[$newChild->get('name')] = $newChild;
			}
		}
		$this->set('children', $newChildren);
		$newChild->setParent($this);
		return $this;
	}
	
	function remove($childName) {
		$children = $this->get('children');
		if(isset($children[$childName])) {
			unset($children[$childName]);
		}
		return $this;
	}
	
	function control($childName) {
		$children = $this->get('children');
		
		if(($dotPos = strpos($childName,'.')) !== false) {
			$childPart = substr($childName,0,$dotPos);
			if(isset($children[$childPart])) {
				return $children[$childPart]->control(substr($childName,$dotPos+1));
			} else {
				return null;
			}
		} else {
			if(isset($children[$childName])) {
				return $children[$childName];
			} else {
				return null;
			}
		}
	}
	
	function set($name, $value) {
		if($name == 'value') {
			$focus = false;
			
			$containerName = $this->get('name');
			if(is_array($value) && isset($value[$containerName])) {
				$focus = $value[$containerName];
			} else if(is_object($value) || is_array($value)) {
				$focus = $value;
			} else {
				return $this;
			}
			
			if($focus != false) {
				$children = $this->get('children');
				foreach($focus as $child => $childValue) {
					if(isset($children[$child]) && $childValue !== null) {
						$children[$child]->set('value', $childValue);
					}
				}
			}
		} else {
			try {
				parent::set($name, $value);
			} catch (InputDoesNotExistException $e) {
				throw new InputDoesNotExistException($e->getMessage(), 1);	
			} catch (InputTypeCheckException $e) {
				throw new InputTypeCheckException($e->getMessage(), 1);	
			}
		}
		return $this;
	}
	
	function get($name) {
		if($name == 'value') {
			$return = new stdclass;
			foreach($this->get('children') as $name => $child) {
				$return->$name = $child->get('value');
				if($return->$name == '__true__') {
					$return->$name = true;
				} else if ($return->$name == '__false__') {
					$return->$name = false;
				}
			}
			return $return;
		} else {
			return parent::get($name);
		}
	}
	
	function getFormNameFor(Control $control) {
		$controlName = $control->get('name');
		if(isset($this->parent)) {
			return $this->parent->getFormNameFor($this) . "[$controlName]";
		} else {
			$thisName = $this->get('name');
			if($thisName === '') {
				return $controlName;
			} else {
				return $thisName.'['.$controlName.']';
			}
		}
	}
	
	function getIdFor(Control $control) {
		$name = $this->getFormNameFor($control);
		return preg_replace(array('/([\[])/','/([\]])/'), array('-', ''), $name);
	}
}
?>