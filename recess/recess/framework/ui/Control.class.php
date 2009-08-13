<?php
Library::import('recess.framework.helpers.blocks.PartBlock');
Library::import('recess.framework.ui.HtmlAttributes');
Library::import('recess.framework.ui.HtmlClasses');
Library::import('recess.framework.ui.Container');

class Control extends PartBlock {
	
	public static $defaultSkin = 'skins/default';
	
	protected $parent;
	protected $skin; 
	
	public function __construct($part, $name, $value = '', $label = false) {
		$part = 'controls/' . $part;
		if($label === false) {
			$label = Inflector::toEnglish($name);
		}
		
		if($value === true) {
			$value = '__true__';
		} else if($value === false) {
			$value = '__false__';
		}
		
		parent::__construct($part, $this, $name, $value, $label);
	}
	
	public static function make($part, $name, $value = '', $label = false) {
		return new Control($part, $name, $value, $label);
	}
	
	// Special draw clones the part, uses the computed name property
	public function draw() {
		$args = func_get_args();
		$clone = clone $this;
		$clone->set('name',	$this->getFormName());
		$clone->set('id',	$this->getId());
		$clone->curry($args);
		try {
			Part::drawArray($clone->partPath, $clone->args);
			return true;
		} catch(MissingRequiredInputException $e) {
			throw new MissingRequiredDrawArgumentsException($e->getMessage(), 1);
		}
	}
	
	public function setParent(Container $parent) {
		$this->parent = $parent;
	}
		
	public function getParent() {
		return $this->parent();
	}
	
	public function getFormName() {
		if(isset($this->parent)) {
			return $this->parent->getFormNameFor($this);
		} else {
			return $this->get('name');
		}
	}
	
	public function getId() {
		if(isset($this->parent)) {
			return $this->parent->getIdFor($this);
		} else {
			return $this->get('name');
		}
	}
	
	public function setSkin($skin) {
		$this->skin = $skin;
		return $this;
	}
	
	public function getSkin() {
		if($this->skin != '') {
			return $this->skin;
		} else if(isset($this->parent)) {
			return $this->parent->get('defaultSkin');
		} else {
			return self::$defaultSkin;
		}
	}
}
?>