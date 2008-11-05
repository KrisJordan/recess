<?php
Library::import('recess.sources.db.orm.relationships.Relationship');

class BelongsToRelationship extends Relationship {
	
	function init($modelClassName, $relationshipName) {
		$this->localClass = $modelClassName;
		$this->name = $relationshipName;
		$this->foreignKey = $relationshipName . '_id';
		$this->foreignClass = Inflector::toProperCaps($relationshipName);
	}
	
	function attachMethodsToModelDescriptor(ModelDescriptor &$descriptor) {
		$attachedMethod = new RecessClassAttachedMethod($this, 'selectModel');
		$descriptor->addAttachedMethod($this->name, $attachedMethod);
	}
	
	protected function augmentSelect(PdoDataSet $select) {
		$select	->from(Model::tableFor($this->foreignClass))
				->innerJoin(Model::tableFor($this->localClass), 
							Model::primaryKeyFor($this->foreignClass), 
							$this->foreignKey);

		$select->rowClass = $this->foreignClass;
		
		if(isset($select[0])) {
			return $select[0];
		} else { 
			return null;
		}
	}
	
	function selectModel(Model $model) {
		return $this->augmentSelect($model->select());
	}
	
	function selectModelSet(ModelSet $modelSet) {
		return $this->augmentSelect($modelSet);
	}

}

?>