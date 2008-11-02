<?php

Library::import('recess.sources.db.pdo.MutableSelectSet');

class MutableModelSet extends MutableSelectSet {
	
//	protected $modelClass;
	
	function __call($name, $arguments) {
		$thisOrm = OrmRegistry::infoForClass($this->rowClass);
		if(isset($thisOrm->relationships[$name])) {
			return $thisOrm->relationships[$name]->augmentSelect($this);
		} else {
			throw new RecessException('Relationship "' . $name . '" does not exist.', get_defined_vars());
		}
	}
	
//	function setModelClass($modelClass) {
//		$this->modelClass = get_class($modelClass);
//	}
	
//	function from($table) {
//		// may need to do a reverse table look up to change the model class
//	}
	
}

?>