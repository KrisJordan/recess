<?php
Library::import('recess.sources.db.orm.relationships.Relationship');

class HasAndBelongsToManyRelationship extends Relationship {
	protected $joinTable;
	protected $joinTableForeignClassKey;
	protected $joinTableLocalClassKey;
	
	function fromAnnotationForClass(Annotation $annotation, $class) {
		$this->localClass = $class;
		
		$settings = $annotation->settings;
		
		if(is_array($settings) && count($settings > 0)) {
			$this->name = $settings[0];
			
			if(isset($settings['ForeignKey'])) {
				$this->foreignKey = $settings['ForeignKey'];
			} else {
				$this->foreignKey = Inflector::toUnderscores($class) . '_id';
			}
			
			if(isset($settings['Class'])) {
				$this->foreignClass = $settings['Class'];
			} else {
				$this->foreignClass = Inflector::toSingular(Inflector::toProperCaps($this->name));
			}
			
			if(isset($settings['Through'])) {
				$this->joinTable = $settings['Through'];	
			} else {
				// TODO: This is a poor heuristic, there's a circular dependency
				//		 in doing this correctly. Could be solved lazily.
				$tables = array($this->localClass, $this->foreignClass);
				sort($tables);
				$this->joinTable = Inflector::toPlural(Inflector::toUnderscores($tables[0])) 
									. '_' 
									. Inflector::toPlural(Inflector::toUnderscores($tables[1]));
			}
			
			$this->joinTableForeignClassKey = $this->joinTable . '.' . Inflector::toUnderscores($this->foreignClass) . '_id';
			$this->joinTableLocalClassKey = $this->joinTable . '.' . Inflector::toUnderscores($class) . '_id';
			
			
			
		} else {
			throw new RecessException('Invalid HasMany Relationship', get_defined_vars());
		}
		
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