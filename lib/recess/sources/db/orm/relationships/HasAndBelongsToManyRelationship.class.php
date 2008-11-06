<?php
Library::import('recess.sources.db.orm.relationships.Relationship');

class HasAndBelongsToManyRelationship extends Relationship {
	protected $joinTable;
	protected $joinTableForeignClassKey;
	protected $joinTableLocalClassKey;
	
	function init($modelClassName, $relationshipName) {
		$this->localClass = $modelClassName;
		$this->name = $relationshipName;
		$this->foreignKey = $relationshipName . '_id';
		$this->onDelete = Relationship::DELETE;
		$this->foreignClass = Inflector::toSingular(Inflector::toProperCaps($this->name));
		
		$tables = array($this->localClass, $this->foreignClass);
		
		sort($tables);
		$this->joinTable = Inflector::toPlural(Inflector::toUnderscores($tables[0])) 
							. '_' 
							. Inflector::toPlural(Inflector::toUnderscores($tables[1]));

		$this->joinTableForeignClassKey = $this->joinTable . '.' . Inflector::toUnderscores($this->foreignClass) . '_id';
		$this->joinTableLocalClassKey = $this->joinTable . '.' . Inflector::toUnderscores($this->localClass) . '_id';
	}
	
	function attachMethodsToModelDescriptor(ModelDescriptor &$descriptor) {
		$attachedMethod = new RecessClassAttachedMethod($this, 'selectModel');
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
		
		if(!$relatedModel->primaryKeyIsSet()) {
			$relatedModel->insert();
		}
		
		$modelPk = Model::primaryKeyName($model);
		$relatedModelPk = Model::primaryKeyName($relatedModel);
		
		$queryBuilder = new SqlBuilder();
		$queryBuilder
			->into($this->joinTable)
			->assign($this->joinTableLocalClassKey, $model->$modelPk)
			->assign($this->joinTableForeignClassKey, $relatedModel->$relatedModelPk);

		$source = Model::sourceFor($model);
		
		$source->executeStatement($queryBuilder->insert(), $queryBuilder->getPdoArguments());
		
		return $model;
	}
	
	function removeFrom(Model $model, Model $relatedModel) {
		$modelPk = Model::primaryKeyName($model);
		$relatedModelPk = Model::primaryKeyName($relatedModel);
		
		$queryBuilder = new SqlBuilder();
		$queryBuilder
			->from($this->joinTable)
			->equal($this->joinTableLocalClassKey, $model->$modelPk)
			->equal($this->joinTableForeignClassKey, $relatedModel->$relatedModelPk);

		$source = Model::sourceFor($model);
		
		$source->executeStatement($queryBuilder->delete(), $queryBuilder->getPdoArguments());
		
		return $model;
	}
	
	protected function augmentSelect(PdoDataSet $select) {
		$select	->from(Model::tableFor($this->foreignClass))
				->distinct()
				->innerJoin($this->joinTable, 
							Model::primaryKeyFor($this->foreignClass), 
							$this->joinTableForeignClassKey)
				->innerJoin(Model::tableFor($this->localClass),
							Model::primaryKeyFor($this->localClass),
							$this->joinTableLocalClassKey);
		$select->rowClass = $this->foreignClass;
		return $select;
	}
	
	function selectModel(Model $model) {
		return $this->augmentSelect($model->select());
	}
	
	function selectModelSet(ModelSet $modelSet) {
		return $this->augmentSelect($modelSet);
	}
	
	function onDeleteCascade(Model $model) {
		
	}
	
	function onDeleteDelete(Model $model) {
			
	}
	
	function onDeleteNullify(Model $model) {
		// no-op
	}

}

?>