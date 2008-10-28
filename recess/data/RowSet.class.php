<?php

Library::import('recess.Box');
Library::import('recess.data.QueryModel');

class RowSet implements Iterator, Countable, ArrayAccess {
	protected $hasResults = false;
	
	protected $query;
	protected $results = array();
	
	public $rowClass = 'stdClass';
	
	private $driver;
	
	public function __construct(Driver $driver) {
		$this->query = new QueryModel();
		$this->driver = $driver;
	}
	
	protected function reset() {
		$this->hasResults = false;
		$this->index = 0;
	}
	
	protected function realize() {
		if(!$this->hasResults) {
			unset($this->results);
			$this->results = $this->driver->query($this->query, 'Box');
			$this->hasResults = true;
		}
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
	
	function from($table) { $this->reset(); $this->query->from($table); return $this; }
	// function count() { $this->reset(); $this->query->count($count); return $this; }
	function select($options) { $this->reset(); $this->query->select($select); return $this; }
	function equal($lhs, $rhs){ $this->reset(); $this->query->equal($lhs,$rhs); return $this; }
	function notEqual($lhs, $rhs) { $this->reset(); $this->query->notEqual($lhs,$rhs); return $this; }
	function between ($column, $lhs, $rhs) { $this->reset(); $this->query->between($column, $lhs, $hrs); return $this; }
	function gt($lhs, $rhs) { $this->reset(); $this->query->gt($lhs,$rhs); return $this; }
	function gte($lhs, $rhs) { $this->reset(); $this->query->gte($lhs,$rhs); return $this; }
	function lt($lhs, $rhs) { $this->reset(); $this->query->lt($lhs,$rhs); return $this; }
	function lte($lhs, $rhs) { $this->reset(); $this->query->lte($lhs,$rhs); return $this; }
	function like($lhs, $rhs) { $this->reset(); $this->query->like($lhs,$rhs); return $this; }
	function where($lhs, $rhs, $operator) { $this->reset(); $this->query->where($lhs,$rhs,$operator); return $this; }
	function limit($size) { $this->reset(); $this->query->limit($size); return $this; }
	function offset($offset) { $this->reset(); $this->query->offset($offset); return $this; }
	function range($start, $finish) { $this->reset(); $this->query->range($start,$finish); return $this; }
	function orderBy($clause) { $this->reset(); $this->query->orderBy($clause); return $this; }
}
?>