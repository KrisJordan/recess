<?php
Library::import('recess.sources.db.orm.relationships.Relationship');

class HasManyRelationship extends Relationship {
	
	function getType() {
		return 'HasMany';
	}
	
	function init($modelClassName, $relationshipName) {
		$this->localClass = $modelClassName;
		$this->name = $relationshipName;
		$this->foreignKey = Inflector::toUnderscores($modelClassName) . '_id';
		$this->foreignClass = Inflector::toSingular(Inflector::toProperCaps($relationshipName));
		$this->onDelete = Relationship::UNSPECIFIED;
	}
	
	function getDefaultOnDeleteMode() {
		if(!isset($this->through)) {
			return Relationship::CASCADE;
		} else {
			return Relationship::DELETE;
		}
	}
	
	function attachMethodsToModelDescriptor(ModelDescriptor &$descriptor) {
		$alias = $this->name;
		$attachedMethod = new RecessClassAttachedMethod($this,'selectModel', $alias);
		$descriptor->addAttachedMethod($alias, $attachedMethod);
		
		$alias = 'addTo' . ucfirst($this->name);
		$attachedMethod = new RecessClassAttachedMethod($this,'addTo', $alias);
		$descriptor->addAttachedMethod($alias, $attachedMethod);
		
		$alias = 'removeFrom' . ucfirst($this->name);
		$attachedMethod = new RecessClassAttachedMethod($this,'removeFrom', $alias);
		$descriptor->addAttachedMethod($alias, $attachedMethod);
	}
	
	function addTo(Model $model, Model $relatedModel) {
		if(!$model->primaryKeyIsSet()) {
			$model->insert();
		}
			
		if(!isset($this->through)) {
			$foreignKey = $this->foreignKey;
			$localKey = Model::primaryKeyName($model);	
			$relatedModel->$foreignKey = $model->$localKey;
			$relatedModel->save();
		} else {
			if(!$relatedModel->primaryKeyIsSet()) {
				$relatedModel->insert();
			}
			// TODO: This is a shitshow.
			$through = new $this->through;
			$localPrimaryKey = Model::primaryKeyName($model);
			$localForeignKey = $this->foreignKey;
			$through->$localForeignKey = $model->$localPrimaryKey;
			
			$relatedPrimaryKey = Model::primaryKeyName($this->through);
			$relatedForeignKey = Model::getRelationship($this->through, Inflector::toSingular($this->name))->foreignKey;
			$through->$relatedForeignKey = $relatedModel->$relatedPrimaryKey;
			
			$through->insert();
		}
		
		return $model;
	}
	
	function removeFrom(Model $model, Model $relatedModel) {
		if(!isset($this->through)) {
			$foreignKey = $this->foreignKey;
			$relatedModel->$foreignKey = '';
			$relatedModel->save();
			return $model;
		} else {
			$through = new $this->through;
			
			$localPrimaryKey = Model::primaryKeyName($model);
			$localForeignKey = $this->foreignKey;
			$through->$localForeignKey = $model->$localPrimaryKey;
			
			$relatedPrimaryKey = Model::primaryKeyName($this->through);
			$relatedForeignKey = Model::getRelationship($this->through, Inflector::toSingular($this->name))->foreignKey;
			$through->$relatedForeignKey = $relatedModel->$relatedPrimaryKey;
			
			$through->find()->delete(false);
		}
	}
	
	function selectModel(Model $model) {
		return $this->augmentSelect($model->select());
	}
	
	function selectModelSet(ModelSet $modelSet) {
		return $this->augmentSelect($modelSet);
	}
	
	protected function augmentSelect(PdoDataSet $select) {
		if(!isset($this->through)) {
			$relatedClass = $this->foreignClass;
		} else {
			$relatedClass = $this->through;
		}
		
		$select = $select	
					->from(Model::tableFor($relatedClass))
					->innerJoin(Model::tableFor($this->localClass),
								Model::tableFor($relatedClass) . '.' . $this->foreignKey, 
								Model::primaryKeyFor($this->localClass) 
								);
		$select->rowClass = $relatedClass;
		
		if(!isset($this->through)) {
			return $select;
		} else {
			$select = $select->distinct();
			$relationship = $this->name;
			return $select->$relationship();
		}
	}
	
	function onDeleteCascade(Model $model) {
		$related = $this->selectModel($model)->delete();
		
		if(isset($this->through)) {
			$modelPk = Model::primaryKeyName($model);
			$queryBuilder = new SqlBuilder();
			$queryBuilder
				->from(Model::tableFor($this->through))
				->equal($this->foreignKey, $model->$modelPk);
			
			$source = Model::sourceFor($model);
			
			$source->executeStatement($queryBuilder->delete(), $queryBuilder->getPdoArguments());		
		}
	}
	
	function onDeleteDelete(Model $model) {
		$modelPk = Model::primaryKeyName($model);
		
		if(!isset($this->through)) {
			$relatedClass = $this->foreignClass;
		} else {
			$relatedClass = $this->through;
		}
		
		$queryBuilder = new SqlBuilder();
		$queryBuilder
			->from(Model::tableFor($relatedClass))
			->equal($this->foreignKey, $model->$modelPk);
		
		$source = Model::sourceFor($model);
		
		$source->executeStatement($queryBuilder->delete(), $queryBuilder->getPdoArguments());
	}
	
	function onDeleteNullify(Model $model) {
		if(isset($this->through)) {
			return $this->onDeleteDelete($model);
		}
		
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