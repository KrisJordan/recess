<?php
Library::import('recess.sources.db.sql.SqlBuilder');
Library::import('recess.sources.db.sql.ISqlSelectOptions');
class PdoDataSet implements Iterator, Countable, ArrayAccess, ISqlSelectOptions, ISqlConditions {
	protected $hasResults = false;
	
	protected $sqlBuilder;
	protected $results = array();
	
	public $rowClass = 'stdClass';
	
	protected $source;
	
	public function __construct(PdoDataSource $source) {
		$this->sqlBuilder = new SqlBuilder();
		$this->source = $source;
	}
	
	public function __clone() {
		$this->sqlBuilder = clone $this->sqlBuilder;
		$this->hasResults = false;
		$this->results = array();
		$this->index = 0;
	}
	
	protected function reset() {
		$this->hasResults = false;
		$this->index = 0;
	}
	
	protected function realize() {
		if(!$this->hasResults) {
			unset($this->results);
			$this->results = $this->source->queryForClass($this->sqlBuilder->select(), $this->sqlBuilder->getPdoArguments(), $this->rowClass);
			$this->hasResults = true;
		}
	}
	
	public function toSql() {
		return $this->sqlBuilder->select();
	}
	
	public function toArray() {
		$this->realize();
		return $this->results;
	}
	
	public function count() { return iterator_count($this); }
	
	protected $index = 0;
	public function rewind() {
		if(!$this->hasResults) {$this->realize();}
		$this->index = 0;
	}
	
	public function current() {
		if(!$this->hasResults) {$this->realize();}
		return $this->results[$this->index];
	}
	
	public function key() {
		if(!$this->hasResults) {$this->realize();}
		return $this->index;
	}
	
	public function next() { 
		if(!$this->hasResults) {$this->realize();}
		$this->index++;
	} 
	
	public function valid() {
		if(!$this->hasResults) {$this->realize();}
		return isset($this->results[$this->index]);
	}
	
	function offsetExists($index) {
		if(!$this->hasResults) {$this->realize();}
		return isset($this->results[$index]);
	}

	function offsetGet($index) {
		if(!$this->hasResults) {$this->realize();}
		if(isset($this->results[$index])) {
			return $this->results[$index];
		} else {
			throw new OutOfBoundsException();
		}
	}

	function offsetSet($index, $value) {
		if(!$this->hasResults) {$this->realize();}
		$this->results[$index] = $value;
	}

	function offsetUnset($index) {
		if(!$this->hasResults) {$this->realize();}
		if(isset($this->results[$index])) {
			unset($this->results[$index]);
		}
	}
	
	function isEmpty() {
		return !isset($this[0]);
	}
	
	function first() { // TODO: DO these semantics make sense? Should we tack on a range?
		if(!$this->hasResults) {
			$this->range(0,1);
		}
		
		if(isset($this[0])) {
			return $this[0];
		} else {
			return null; // TODO: This should probably throw something.
		}
	}
	
	function assign($column, $value) { $copy = clone $this; $copy->sqlBuilder->assign($column, $value); return $copy; }
	function useAssignmentsAsConditions($bool) { $copy = clone $this; $copy->sqlBuilder->useAssignmentsAsConditions($bool); return $copy; }
	
	function from($table) { $copy = clone $this; $copy->sqlBuilder->from($table); return $copy; }
	function leftOuterJoin($table, $tablePrimaryKey, $fromTableForeignKey) { $copy = clone $this; $copy->sqlBuilder->leftOuterJoin($table, $tablePrimaryKey, $fromTableForeignKey); return $copy; }
	function innerJoin($table, $tablePrimaryKey, $fromTableForeignKey) { $copy = clone $this; $copy->sqlBuilder->innerJoin($table, $tablePrimaryKey, $fromTableForeignKey); return $copy; }
	function select($options) { $copy = clone $this; $copy->sqlBuilder->select($select); return $copy; }
	function distinct() { $copy = clone $this; $copy->sqlBuilder->distinct(); return $copy; }
	function equal($lhs, $rhs){ $copy = clone $this; $copy->sqlBuilder->equal($lhs,$rhs); return $copy; }
	function notEqual($lhs, $rhs) { $copy = clone $this; $copy->sqlBuilder->notEqual($lhs,$rhs); return $copy; }
	function between ($column, $lhs, $rhs) { $copy = clone $this; $copy->sqlBuilder->between($column, $lhs, $hrs); return $copy; }
	function greaterThan($lhs, $rhs) { $copy = clone $this; $copy->sqlBuilder->greaterThan($lhs,$rhs); return $copy; }
	function greaterThanOrEqualTo($lhs, $rhs) { $copy = clone $this; $copy->sqlBuilder->greaterThanOrEqualTo($lhs,$rhs); return $copy; }
	function lessThan($lhs, $rhs) { $copy = clone $this; $copy->sqlBuilder->lessThan($lhs,$rhs); return $copy; }
	function lessThanOrEqualTo($lhs, $rhs) { $copy = clone $this; $copy->sqlBuilder->lessThanOrEqualTo($lhs,$rhs); return $copy; }
	function like($lhs, $rhs) { $copy = clone $this; $copy->sqlBuilder->like($lhs,$rhs); return $copy; }
	function where($lhs, $rhs, $operator) { $copy = clone $this; $copy->sqlBuilder->where($lhs,$rhs,$operator); return $copy; }
	function limit($size) { $copy = clone $this; $copy->sqlBuilder->limit($size); return $copy; }
	function offset($offset) { $copy = clone $this; $copy->sqlBuilder->offset($offset); return $copy; }
	function range($start, $finish) { $copy = clone $this; $copy->sqlBuilder->range($start,$finish); return $copy; }
	function orderBy($clause) { $copy = clone $this; $copy->sqlBuilder->orderBy($clause); return $copy; }
}
?>