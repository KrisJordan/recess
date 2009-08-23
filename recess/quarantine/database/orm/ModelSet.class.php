<?php

Library::import('recess.database.pdo.PdoDataSet');

class ModelSet extends PdoDataSet {
	
	function __call($name, $arguments) {
		$relationship = Model::getRelationship($this->rowClass, $name);
		if($relationship === false && Inflector::isPlural($name)) {
			$name = Inflector::toSingular($name);
			$relationship = Model::getRelationship($this->rowClass, $name);
			if(!$relationship instanceof BelongsToRelationship) {
				$relationship = false;
			}
		}
		
		if($relationship !== false) {
			return $relationship->selectModelSet($this);
		} else {
			throw new RecessException('Relationship "' . $name . '" does not exist.', get_defined_vars());
		}
	}
	
	function update() {
		return $this->source->executeStatement($this->sqlBuilder->useAssignmentsAsConditions(false)->update(), $this->sqlBuilder->getPdoArguments());
	}
	
	function delete($cascade = true) {
		foreach($this as $model) {
			$model->delete($cascade);
		}
	}
}

?>