<?php
Library::import('recess.utility.Inflector');
Library::import('recess.sources.db.DbSources');
Library::import('recess.sources.db.orm.OrmRegistry');

Library::import('recess.sources.db.orm.annotations.HasManyAnnotation', true);
Library::import('recess.sources.db.orm.annotations.BelongsToAnnotation', true);

abstract class Model extends stdClass {
	
	public function __call($name, $args) {
		$thisOrm = OrmRegistry::infoForObject($this);
		if(isset($thisOrm->relationships[$name])) {
			$result = $thisOrm->relationships[$name]->augmentSelect($this->beginSelect());
			return $result;
		} else {
			throw new RecessException('Relationship "' . $name . '" does not exist.', get_defined_vars());
		}
	}
	
	public function find() {
		return $this->beginSelect();
	}
	
	protected function beginSelect() {
		$thisOrm = OrmRegistry::infoForObject($this);
		$result = $thisOrm->source->select($thisOrm->table);
		foreach($this as $column => $value) {
			if(in_array($column,$thisOrm->columns)) {
				$result->equal($column, $value);
			}
		}
		$result->rowClass = $thisOrm->class;
		return $result;
	}

}



?>