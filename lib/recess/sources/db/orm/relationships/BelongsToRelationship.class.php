<?php
Library::import('recess.sources.db.orm.relationships.Relationship');

class BelongsToRelationship extends Relationship {
	
	function init($modelClassName, $relationshipName) {
		$this->localClass = $modelClassName;
		$this->name = $relationshipName;
		$this->onDelete = Relationship::NULLIFY;
		$this->foreignKey = $relationshipName . '_id';
		$this->foreignClass = Inflector::toProperCaps($relationshipName);
	}
	
	function attachMethodsToModelDescriptor(ModelDescriptor &$descriptor) {
		$attachedMethod = new RecessClassAttachedMethod($this, 'selectModel');
		$descriptor->addAttachedMethod($this->name, $attachedMethod);
		
		$attachedMethod = new RecessClassAttachedMethod($this,'set');
		$descriptor->addAttachedMethod('set' . ucfirst($this->name), $attachedMethod);
		
		$attachedMethod = new RecessClassAttachedMethod($this,'remove');
		$descriptor->addAttachedMethod('unset' . ucfirst($this->name), $attachedMethod);
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
		$model->$foreignKey = null;
		$model->save();
		
		return $model;
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
}

?>