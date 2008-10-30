<?php
class Criteria extends Box {
	
	public function __construct($lhs, $rhs, $operator){
		$this->lhs = $lhs;
		$this->rhs = $rhs;
		$this->operator = $operator;
	}
}

class QueryModel  {
	public $table;
	public $joins;
	public $where = array();
	public $limit;
	public $offset;
	public $orderBy = array();
	public $select = '*';
	public $key;
	
	public function __toString(){ $this->getSql(); }
	
	public function getSql() {
		$query = 'SELECT ' . $this->select . ' FROM ' . $this->table;
		if(!empty($this->where)) {
			$query .= ' WHERE ';
			$first = true;
			foreach($this->where as $clause) {
				if(!$first) { $query .= ' AND '; } else { $first = false; }
				//we're not binding here becuase we haven't decided how that will work
				$query .= $clause->lhs . ' ' . $clause->operator . ' :' . $clause->lhs;
			}
		}
		if(!empty($this->orderBy)){
			$query .= ' ORDER BY ';
			$first = true;
			foreach($this->orderBy as $order){
				if(!$first) { $query .= ', '; } else { $first = false; }
				$query .= $order;
			}
		}
		if(isset($this->limit)){ $query .= ' LIMIT ' . $this->limit; }
		if(isset($this->offset)){ $query .= ' OFFSET ' . $this->offset; }
		return $query;
	}
	
	public function from($table) { $this->table = $table; return $this; }
	public function join($table, $joinPrimaryKey, $joinTableForeignKey) { }
	
	public function key($column) { $this->key = $column; }
	

	public function count() { $this->select = "COUNT(*)"; return $this; }
	
	// Criteria
	public function equal($lhs, $rhs)       { return $this->where($lhs, $rhs, '='); }
	public function notEqual($lhs, $rhs)    { return $this->where($lhs, $rhs, '!='); }
	public function between ($column, $lhs, $rhs) { $this->gt($column, $lhs); return $this->lt($column, $rhs); }
	public function gt($lhs, $rhs)          { return $this->where($lhs, $rhs, '>'); }
	public function gte($lhs, $rhs)         { return $this->where($lhs, $rhs, '>='); }
	public function lt($lhs, $rhs)          { return $this->where($lhs, $rhs, '<'); }
	public function lte($lhs, $rhs)         { return $this->where($lhs, $rhs, '<='); }
	public function like($lhs, $rhs)        { return $this->where($lhs, $rhs, 'LIKE'); }
	
	protected function where($lhs, $rhs, $operator) { $this->where[] = new Criteria($lhs, $rhs, $operator); return $this; }
	
	public function limit($size)           { $this->limit = $size; return $this; }
	public function offset($offset)        { $this->offset = $offset; return $this; }
	public function range($start, $finish) { $this->offset = $start; $this->limit = $finish - $start; return $this; }
	
	public function orderBy($clause)       { $this->orderBy[] = $clause; return $this; }
	
}
?>