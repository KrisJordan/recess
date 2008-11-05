<?php
Library::import('recess.sources.db.orm.relationships.Relationship');

class HasManyRelationship extends Relationship {
	
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
			
		} else {
			throw new RecessException('Invalid HasMany Relationship', get_defined_vars());
		}
		
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