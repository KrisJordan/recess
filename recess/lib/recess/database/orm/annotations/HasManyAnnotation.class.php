<?php
Library::import('recess.database.orm.annotations.ModelAnnotation');
Library::import('recess.database.orm.relationships.HasManyRelationship');

class HasManyAnnotation extends ModelAnnotation {
	protected $relationshipName;
	protected $settings = array();
	
	function init($array) {
		if(count($array) < 1) {
			throw new RecessException('HasMany annotation requires at least a name: /** HasMany nameOfRelationship */');
		}
		$this->relationshipName = array_shift($array);
		$this->settings = $array;
	}
	
	function massage(ModelDescriptor &$descriptor) {
		$relationship = new HasManyRelationship();
		$this->massageRelationshipHelper($descriptor, $relationship);
	}

}
?>