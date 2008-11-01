<?php

abstract class Relationship {
	public $name;
	public $localClass;
	public $foreignClass;
	public $foreignKey;
	
	abstract function fromAnnotationForClass(Annotation $attribute, $class);
	
	abstract function augmentSelect(SelectedSet $select);
}

?>