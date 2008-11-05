<?php

abstract class Relationship {
	public $name;
	public $localClass;
	public $foreignClass;
	public $foreignKey;
	
	// TODO: make this abstract
	function init(ModelDescriptor $descriptor) {}
	
	abstract function selectModel(Model $model);
	abstract function selectModelSet(ModelSet $modelSet);
}

?>