<?php
Library::import('recess.database.orm.annotations');

class BelongsToAnnotation extends ModelAnnotation {
	protected $relationshipName;
	protected $settings = array();
	
	function init($array) {
		if(count($array) < 1) {
			throw new RecessException('BelongsTo annotation requires at least a name: /** BelongsTo nameOfRelationship */');
		}
		$this->relationshipName = array_shift($array);
		$this->settings = $array;
	}
	
	function massage(ModelDescriptor &$descriptor) {
		$relationship = new BelongsToRelationship();
		$this->massageRelationshipHelper($descriptor, $relationship);
	}

}
?>