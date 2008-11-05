<?php

abstract class Relationship {
	public $name;
	public $localClass;
	public $foreignClass;
	public $foreignKey;
	
	// TODO: Get rid of following line
	function fromAnnotationForClass(Annotation $attribute, $class) {}
	// TODO: make this abstract
	function init(ModelDescriptor $descriptor) {}
	
	abstract function selectModel(Model $model);
	abstract function selectModelSet(ModelSet $modelSet);
}

?>