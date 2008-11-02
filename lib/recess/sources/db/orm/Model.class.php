<?php
Library::import('recess.utility.Inflector');
Library::import('recess.sources.db.DbSources');
Library::import('recess.sources.db.orm.OrmRegistry');

Library::import('recess.sources.db.orm.annotations.HasManyAnnotation', true);
Library::import('recess.sources.db.orm.annotations.BelongsToAnnotation', true);
Library::import('recess.sources.db.orm.annotations.HasAndBelongsToManyAnnotation', true);
Library::import('recess.sources.db.orm.annotations.TableAnnotation', true);

abstract class Model extends stdClass {
	
	public function __call($name, $args) {
		$thisOrm = OrmRegistry::infoForObject($this);
		if(isset($thisOrm->relationships[$name])) {
			return $thisOrm->relationships[$name]->augmentSelect($this->beginSelect());
		} else {
			throw new RecessException('Relationship "' . $name . '" does not exist.', get_defined_vars());
		}
	}
	

	
	// TODO: NEEDS TO RETURN A MUTABLE MODEL SET
	protected function beginSelect() {
		$thisOrm = OrmRegistry::infoForObject($this);
		$result = $thisOrm->source->selectModelSet($thisOrm->table);
		foreach($this as $column => $value) {
			if(in_array($column,$thisOrm->columns)) {
				$result->equal($column, $value);
			}
		}
		$result->rowClass = $thisOrm->class;
		return $result;
	}
	
	function find() { return $this->beginSelect(); }
	function equal($lhs, $rhs){ return $this->beginSelect()->equal($lhs,$rhs); }
	function notEqual($lhs, $rhs) { return $this->beginSelect()->notEqual($lhs,$rhs); }
	function between ($column, $lhs, $rhs) { return $this->beginSelect()->between($column, $lhs, $hrs); }
	function greaterThan($lhs, $rhs) { return $this->beginSelect()->greaterThan($lhs,$rhs); }
	function greaterThanOrEqualTo($lhs, $rhs) { return $this->beginSelect()->greaterThanOrEqualTo($lhs,$rhs); }
	function lessThan($lhs, $rhs) { return $this->beginSelect()->lessThan($lhs,$rhs); }
	function lessThanOrEqualTo($lhs, $rhs) { return $this->beginSelect()->lessThanOrEqualTo($lhs,$rhs); }
	function like($lhs, $rhs) {return $this->beginSelect()->like($lhs,$rhs); }
	function where($lhs, $rhs, $operator) { return $this->beginSelect()->where($lhs,$rhs,$operator); }
	function limit($size) { return $this->beginSelect()->limit($size); }
	function offset($offset) { return $this->beginSelect()->offset($offset); }
	function range($start, $finish) { return $this->beginSelect()->range($start,$finish); }
	function orderBy($clause) { return $this->beginSelect()->orderBy($clause); }
}



?>