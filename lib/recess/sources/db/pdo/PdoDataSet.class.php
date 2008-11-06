<?php
Library::import('recess.sources.db.sql.SqlBuilder');

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
	
	function update() {
		
	}
	
	public function toSql() {
		return $this->sqlBuilder->select();
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
	
	function assign($column, $value) { $this->reset(); $this->sqlBuilder->assign($column, $value); return $this; }
	function useAssignmentsAsConditions($bool) { $this->sqlBuilder->useAssignmentsAsConditions($bool); return $this; }
	
	function from($table) { $this->reset(); $this->sqlBuilder->from($table); return $this; }
	function leftOuterJoin($table, $tablePrimaryKey, $fromTableForeignKey) { $this->reset(); $this->sqlBuilder->leftOuterJoin($table, $tablePrimaryKey, $fromTableForeignKey); return $this; }
	function innerJoin($table, $tablePrimaryKey, $fromTableForeignKey) { $this->reset(); $this->sqlBuilder->innerJoin($table, $tablePrimaryKey, $fromTableForeignKey); return $this; }
	function select($options) { $this->reset(); $this->sqlBuilder->select($select); return $this; }
	function distinct() { $this->reset(); $this->sqlBuilder->distinct(); return $this; }
	function equal($lhs, $rhs){ $this->reset(); $this->sqlBuilder->equal($lhs,$rhs); return $this; }
	function notEqual($lhs, $rhs) { $this->reset(); $this->sqlBuilder->notEqual($lhs,$rhs); return $this; }
	function between ($column, $lhs, $rhs) { $this->reset(); $this->sqlBuilder->between($column, $lhs, $hrs); return $this; }
	function greaterThan($lhs, $rhs) { $this->reset(); $this->sqlBuilder->greaterThan($lhs,$rhs); return $this; }
	function greaterThanOrEqualTo($lhs, $rhs) { $this->reset(); $this->sqlBuilder->greaterThanOrEqualTo($lhs,$rhs); return $this; }
	function lessThan($lhs, $rhs) { $this->reset(); $this->sqlBuilder->lessThan($lhs,$rhs); return $this; }
	function lessThanOrEqualTo($lhs, $rhs) { $this->reset(); $this->sqlBuilder->lessThanOrEqualTo($lhs,$rhs); return $this; }
	function like($lhs, $rhs) { $this->reset(); $this->sqlBuilder->like($lhs,$rhs); return $this; }
	function where($lhs, $rhs, $operator) { $this->reset(); $this->sqlBuilder->where($lhs,$rhs,$operator); return $this; }
	function limit($size) { $this->reset(); $this->sqlBuilder->limit($size); return $this; }
	function offset($offset) { $this->reset(); $this->sqlBuilder->offset($offset); return $this; }
	function range($start, $finish) { $this->reset(); $this->sqlBuilder->range($start,$finish); return $this; }
	function orderBy($clause) { $this->reset(); $this->sqlBuilder->orderBy($clause); return $this; }
}
?>