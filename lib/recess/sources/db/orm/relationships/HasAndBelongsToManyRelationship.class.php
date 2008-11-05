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

}

?>