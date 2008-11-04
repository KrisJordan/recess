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
	
	function augmentSelect(PdoDataSet $select) {
		$select	->from(OrmRegistry::tableFor($this->foreignClass))
				->innerJoin(OrmRegistry::tableFor($this->localClass), 
							OrmRegistry::primaryKeyFor($this->localClass), 
							OrmRegistry::tableFor($this->foreignClass) . '.' . $this->foreignKey);
				
		$select->rowClass = $this->foreignClass;
		return $select;
	}

}

?>