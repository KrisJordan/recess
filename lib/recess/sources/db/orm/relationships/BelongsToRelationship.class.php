<?php
Library::import('recess.sources.db.orm.relationships.Relationship');

class BelongsToRelationship extends Relationship {
	
	function fromAnnotationForClass(Annotation $annotation, $class) {
		$this->localClass = $class;
		
		$settings = $annotation->settings;
		
		if(is_array($settings) && count($settings > 0)) {
			$this->name = $settings[0];
			
			if(isset($settings['ForeignKey'])) {
				$this->foreignKey = $settings['ForeignKey'];
			} else {
				$this->foreignKey = $this->name . '_id';
//				echo $this->name;
//				print_r(debug_backtrace());
			}
			
			if(isset($settings['Class'])) {
				$this->foreignClass = $settings['Class'];
			} else {
				$this->foreignClass = Inflector::toProperCaps($this->name);
			}
			
		} else {
			throw new RecessException('Invalid BelongsTo Relationship', get_defined_vars());
		}
		
	}
	
	function augmentSelect(PdoDataSet $select) {
		$select	->from(OrmRegistry::tableFor($this->foreignClass))
				->innerJoin(OrmRegistry::tableFor($this->localClass), 
							OrmRegistry::primaryKeyFor($this->foreignClass), 
							$this->foreignKey);

		$select->rowClass = $this->foreignClass;
		
		if(isset($select[0])) {
			return $select[0];
		} else { 
			return null;
		}
	}

}

?>