<?php
Library::import('recess.sources.db.orm.relationships.Relationship');

class HasManyRelationship extends Relationship {
	
	function init($modelClassName, $relationshipName) {
		$this->localClass = $modelClassName;
		$this->name = $relationshipName;
		$this->foreignKey = Inflector::toUnderscores($modelClassName) . '_id';
		$this->foreignClass = Inflector::toSingular(Inflector::toProperCaps($relationshipName));
		$this->onDelete = Relationship::CASCADE;
	}
	
	function attachMethodsToModelDescriptor(ModelDescriptor &$descriptor) {
		$attachedMethod = new RecessClassAttachedMethod($this,'selectModel');
		$descriptor->addAttachedMethod($this->name, $attachedMethod);
		
		$attachedMethod = new RecessClassAttachedMethod($this,'addTo');
		$descriptor->addAttachedMethod('addTo' . ucfirst($this->name), $attachedMethod);
		
		$attachedMethod = new RecessClassAttachedMethod($this,'removeFrom');
		$descriptor->addAttachedMethod('removeFrom' . ucfirst($this->name), $attachedMethod);
	}
	
	function addTo(Model $model, Model $relatedModel) {
		if(!$model->primaryKeyIsSet()) {
			$model->insert();
		}
		
		$foreignKey = $this->foreignKey;
		$localKey = Model::primaryKeyName($model);	
		$relatedModel->$foreignKey = $model->$localKey;
		$relatedModel->save();
		return $model;
	}
	
	function removeFrom(Model $model, Model $relatedModel) {
		$foreignKey = $this->foreignKey;
		$relatedModel->$foreignKey = '';
		$relatedModel->save();
		return $model;
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
	
	function onDeleteCascade(Model $model) {
		$this->selectModel($model)->delete();
	}
	
	function onDeleteDelete(Model $model) {		
		$modelPk = Model::primaryKeyName($model);
		
		$queryBuilder = new SqlBuilder();
		$queryBuilder
			->from(Model::tableFor($this->foreignClass))
			->equal($this->foreignKey, $model->$modelPk);
		
		$source = Model::sourceFor($model);
		
		$source->executeStatement($queryBuilder->delete(), $queryBuilder->getPdoArguments());
	}
	
	function onDeleteNullify(Model $model) {
		$modelPk = Model::primaryKeyName($model);
		
		$queryBuilder = new SqlBuilder();
		$queryBuilder
			->from(Model::tableFor($this->foreignClass))
			->assign($this->foreignKey, null)
			->equal($this->foreignKey, $model->$modelPk);
			
		$source = Model::sourceFor($model);
		
		$source->executeStatement($queryBuilder->update(), $queryBuilder->getPdoArguments());
	}

}

?>