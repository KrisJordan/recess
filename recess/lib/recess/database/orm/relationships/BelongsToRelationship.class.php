<?php

class BelongsToRelationship extends Relationship {
	
	function getType() {
		return 'BelongsTo';
	}
	
	function init($modelClassName, $relationshipName) {
		$this->localClass = $modelClassName;
		$this->name = $relationshipName;
		$this->onDelete = Relationship::NULLIFY;
		$this->foreignKey = ModelConventions::relatedForeignKeyFromBelongsToName($relationshipName);
		$this->foreignClass = ModelConventions::relatedClassFromBelongsToName($relationshipName);
	}
	
	function attachMethodsToModelDescriptor(ModelDescriptor &$descriptor) {
		$alias = $this->name;
		$attachedMethod = new RecessObjectAttachedMethod($this, 'selectModel', $alias);
		$descriptor->addAttachedMethod($alias, $attachedMethod);
		
		$alias = 'set' . ucfirst($this->name);
		$attachedMethod = new RecessObjectAttachedMethod($this,'set', $alias);
		$descriptor->addAttachedMethod($alias, $attachedMethod);
		
		$alias = 'unset' . ucfirst($this->name);
		$attachedMethod = new RecessObjectAttachedMethod($this,'remove', $alias);
		$descriptor->addAttachedMethod($alias, $attachedMethod);
	}
	
	function set(Model $model, Model $relatedModel) {
		if(!$relatedModel->primaryKeyIsSet()) {
			$relatedModel->insert();
		}
		
		$foreignKey = $this->foreignKey;
		$relatedPrimaryKey = Model::primaryKeyName($relatedModel);
		$model->$foreignKey = $relatedModel->$relatedPrimaryKey;
		$model->save();
		
		return $model;
	}
	
	function remove(Model $model) {		
		$foreignKey = $this->foreignKey;
		$model->$foreignKey = '';
		$model->save();
		
		return $model;
	}
	
	protected function augmentSelect(PdoDataSet $select) {
		$select = $select	
					->from(Model::tableFor($this->foreignClass))
					->innerJoin(Model::tableFor($this->localClass), 
								Model::primaryKeyFor($this->foreignClass), 
								Model::tableFor($this->localClass) . '.' . $this->foreignKey);
								
		$select->rowClass = $this->foreignClass;
		return $select;
	}
	
	function selectModel(Model $model) {
		$select = $this->augmentSelect($model->select());
		
		if(isset($select[0])) {
			return $select[0];
		} else { 
			return null;
		}
	}
	
	function selectModelSet(ModelSet $modelSet) {
		return $this->augmentSelect($modelSet);
	}
	
	function onDeleteCascade(Model $model) {
		$this->selectModel($model)->delete();
	}
	
	function onDeleteDelete(Model $model) {
		$relatedModel = $this->selectModel($model);
		if($relatedModel != null) {
			$relatedModel->delete(false);		
		}
	}
	
	function onDeleteNullify(Model $model) {
		// no-op
	}
	
	function __set_state($array) {
		$relationship = new BelongsToRelationship();
		$relationship->name = $array['name'];
		$relationship->localClass = $array['localClass'];
		$relationship->foreignClass = $array['foreignClass'];
		$relationship->onDelete = $array['onDelete'];
		$relationship->through = $array['through'];
		return $relationship;
	}
}

?>