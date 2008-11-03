<?php
Library::import('recess.utility.Inflector');
Library::import('recess.sources.db.DbSources');
Library::import('recess.sources.db.orm.OrmRegistry');
Library::import('recess.sources.db.sql.ISqlConditions');

Library::import('recess.sources.db.orm.annotations.HasManyAnnotation', true);
Library::import('recess.sources.db.orm.annotations.BelongsToAnnotation', true);
Library::import('recess.sources.db.orm.annotations.HasAndBelongsToManyAnnotation', true);
Library::import('recess.sources.db.orm.annotations.TableAnnotation', true);

abstract class Model extends stdClass implements ISqlConditions {
	
	public function __call($name, $args) {
		$thisOrm = OrmRegistry::infoForObject($this);
		if(isset($thisOrm->relationships[$name])) {
			return $thisOrm->relationships[$name]->augmentSelect($this->select());
		} else {
			throw new RecessException('Relationship "' . $name . '" does not exist.', get_defined_vars());
		}
	}
	
	function all() { 
		$thisOrm = OrmRegistry::infoForObject($this);
		$result = $thisOrm->source->selectModelSet($thisOrm->table);
		$result->rowClass = $thisOrm->class;
		return $result;
	}

	protected function getModelSet() {
		$thisOrm = OrmRegistry::infoForObject($this);
		$result = $thisOrm->source->selectModelSet($thisOrm->table);
		foreach($this as $column => $value) {
			if(in_array($column,$thisOrm->columns)) {
				$result->assign($column, $value);
			}
		}
		$result->rowClass = $thisOrm->class;
		return $result;
	}
	
	function select() { 
		return $this->getModelSet()->useAssignmentsAsConditions(true);
	}
	
	function delete() {
		$thisOrm = OrmRegistry::infoForObject($this);
		
		$sqlBuilder = new SqlBuilder();
		$sqlBuilder->from($thisOrm->table);
		foreach($this as $column => $value) {
			if(in_array($column,$thisOrm->columns)) {
				$sqlBuilder->equal($column,$value);
			}
		}
		
		return $thisOrm->source->executeStatement($sqlBuilder->delete(), $sqlBuilder->getPdoArguments());	
	}
	
	function insert() {
		$thisOrm = OrmRegistry::infoForObject($this);
		
		$sqlBuilder = new SqlBuilder();
		$sqlBuilder->from($thisOrm->table);
		foreach($this as $column => $value) {
			if(in_array($column,$thisOrm->columns)) {
				$sqlBuilder->assign($column,$value);
			}
		}
		
		return $thisOrm->source->executeStatement($sqlBuilder->insert(), $sqlBuilder->getPdoArguments());
	}
	
	function update() {
		$thisOrm = OrmRegistry::infoForObject($this);
		
		$sqlBuilder = new SqlBuilder();
		$sqlBuilder->from($thisOrm->table);
		foreach($this as $column => $value) {
			if($thisOrm->primaryKey == $column) continue;
			if(in_array($column,$thisOrm->columns)) {
				$sqlBuilder->assign($column,$value);
			}
		}
		$pk = str_replace($thisOrm->table . '.', '', $thisOrm->primaryKey);
		$sqlBuilder->equal($thisOrm->primaryKey, $this->$pk);
		
		return $thisOrm->source->executeStatement($sqlBuilder->update(), $sqlBuilder->getPdoArguments());
	}
	
	/**
	 *
	 * $person = new Person();
	 * $person->id = 5;
	 * $person->city = 'Ohio';
	 * $person->update();
	 *
	 * $person = new Person();
	 * $person->age = 23;
	 * $person->greaterThan('month', 2)->update(); // what if this flips the bit back?
	 * 
	 */
	
	function save()   {  }
	
	function find() { return $this->select(); }
	
	function equal($lhs, $rhs){ return $this->select()->equal($lhs,$rhs); }
	function notEqual($lhs, $rhs) { return $this->select()->notEqual($lhs,$rhs); }
	function between ($column, $lhs, $rhs) { return $this->select()->between($column, $lhs, $hrs); }
	function greaterThan($lhs, $rhs) { return $this->select()->greaterThan($lhs,$rhs); }
	function greaterThanOrEqualTo($lhs, $rhs) { return $this->select()->greaterThanOrEqualTo($lhs,$rhs); }
	function lessThan($lhs, $rhs) { return $this->select()->lessThan($lhs,$rhs); }
	function lessThanOrEqualTo($lhs, $rhs) { return $this->select()->lessThanOrEqualTo($lhs,$rhs); }
	function like($lhs, $rhs) { return $this->select()->like($lhs,$rhs); }
}

?>