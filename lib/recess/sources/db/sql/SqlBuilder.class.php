<?php
Library::import('recess.sources.db.sql.Criterion');

class SqlBuilder  {
	public $from;
	public $joins;
	public $where = array();
	public $limit;
	public $offset;
	public $orderBy = array();
	public $select = '*';
	public $key;
	
	public function __toString(){ $this->getSql(); }
	
	public function getSql() {
		$this->checkSanity();
					
		if($this->select == '*' && !isset($this->from)) return '';
			
		$query = 'SELECT ' . $this->select . ' FROM ' . $this->from;
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
	
	protected function checkSanity() {
		if( (!empty($this->where) || !empty($this->orderBy)) && !isset($this->from))
			throw new RecessException('Must have from if using where.', get_defined_vars());
		
		if( isset($this->offset) && !isset($this->limit))
			throw new RecessException('Must define limit if using offset.', get_defined_vars());
	}
	
	public function from($table) { $this->from = $table; return $this; }
	
	public function leftJoin($table, $tablePrimaryKey, $fromTableForeignKey) { }
	
	public function key($column) { $this->key = $column; }
	
	public function count() { $this->select = "COUNT(*)"; return $this; }
	
	// Criteria
	public function equal($lhs, $rhs)       { return $this->where($lhs, $rhs, Criterion::EQUAL_TO); }
	public function notEqual($lhs, $rhs)    { return $this->where($lhs, $rhs, Criterion::NOT_EQUAL_TO); }
	public function between ($column, $lhs, $rhs) { $this->greaterThan($column, $lhs); return $this->lessThan($column, $rhs); }
	public function greaterThan($lhs, $rhs)          { return $this->where($lhs, $rhs, Criterion::GREATER_THAN); }
	public function greaterThanOrEqualTo($lhs, $rhs)         { return $this->where($lhs, $rhs, Criterion::GREATER_THAN_EQUAL_TO); }
	public function lessThan($lhs, $rhs)          { return $this->where($lhs, $rhs, Criterion::LESS_THAN); }
	public function lessThanOrEqualTo($lhs, $rhs)         { return $this->where($lhs, $rhs, Criterion::LESS_THAN_EQUAL_TO); }
	public function like($lhs, $rhs)        { return $this->where($lhs, $rhs, Criterion::LIKE); }
	
	protected function where($lhs, $rhs, $operator) { $this->where[] = new Criterion($lhs, $rhs, $operator); return $this; }
	
	public function limit($size)           { $this->limit = $size; return $this; }
	public function offset($offset)        { $this->offset = $offset; return $this; }
	public function range($start, $finish) { $this->offset = $start; $this->limit = $finish - $start; return $this; }
	
	public function orderBy($clause)       { $this->orderBy[] = $clause; return $this; }
	
}
?>