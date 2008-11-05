<?php
Library::import('recess.sources.db.orm.annotations');

class HasAndBelongsToManyAnnotation extends ModelAnnotation {
	protected $relationshipName;
	protected $settings = array();
	
	function init($array) {
		if(count($array) < 1) {
			throw new RecessException('HasAndBelongsToMany annotation requires at least a name: /** BelongsTo nameOfRelationship */');
		}
		$this->relationshipName = array_shift($array);
		$this->settings = $array;
	}
	
	function massage(ModelDescriptor &$descriptor) {		
		$relationship = new HasAndBelongsToManyRelationship();
		$this->massageRelationshipHelper($descriptor, $relationship);
	}
}

?>