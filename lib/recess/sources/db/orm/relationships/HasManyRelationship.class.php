<?php
Library::import('recess.sources.db.orm.relationships.Relationship');

class HasManyRelationship extends Relationship {
	
	function init($modelClassName, $relationshipName) {
		$this->localClass = $modelClassName;
		$this->name = $relationshipName;
		$this->foreignKey = Inflector::toUnderscores($modelClassName) . '_id';
		$this->foreignClass = Inflector::toSingular(Inflector::toProperCaps($relationshipName));
	}
	
	function attachMethodsToModelDescriptor(ModelDescriptor &$descriptor) {
		$attachedMethod = new RecessClassAttachedMethod($this,'selectModel');
		$descriptor->addAttachedMethod($this->name, $attachedMethod);
	}
	
	function selectModel(Model $model) {
		return $this->augmentSelect($model->select());
	}
	
	function selectModelSet(ModelSet $modelSet) {
		return $this->augmentSelect($modelSet);
	}
	
	protected function augmentSelect(PdoDataSet $select) {
		$select	->from(Model::tableFor($this->foreignClass))
				->innerJoin(Model::tableFor($this->localClass), 
							Model::primaryKeyFor($this->localClass), 
							Model::tableFor($this->foreignClass) . '.' . $this->foreignKey);
				
		$select->rowClass = $this->foreignClass;
		return $select;
	}

}

?>