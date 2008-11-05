<?php
Library::import('recess.lang.Annotation');

abstract class ModelAnnotation extends Annotation {
	abstract function massage(ModelDescriptor &$descriptor);
	
	final protected function massageRelationshipHelper(ModelDescriptor &$descriptor, Relationship $relationship) {
		$relationship->init($descriptor->modelClass, $this->relationshipName);
		
		foreach($this->settings as $key => $value) {
			switch($key) {
				case 'ForeignKey':
					$relationship->foreignKey = $value;
					break;
				case 'Class':
					$relationship->foreignClass = $value;
					break;
				case 'Through':
					$relationship->joinTable = $value;
					break;		
			}
		}

		$descriptor->relationships[$relationship->name] = $relationship;
		
		$relationship->attachMethodsToModelDescriptor($descriptor);
	}
}
?>