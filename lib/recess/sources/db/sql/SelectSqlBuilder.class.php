<?php
Library::import('recess.sources.db.sql.Criterion');
Library::import('recess.sources.db.sql.Join');

class SelectSqlBuilder  {
	public $from;
	public $joins = array();
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
		
		if(!empty($this->joins)) {
			foreach($this->joins as $join) {
				if(isset($join->natural)) {
					$query .= $join->natural . ' ';
				}
				if(isset($join->leftRightOrFull)) {
					$query .= $join->leftRightOrFull . ' ';
				}
				if(isset($join->innerOuterOrCross)) {
					$query .= $join->innerOuterOrCross . ' ';
				}
				$query .= 'JOIN ';
				$query .= $join->table . ' ON ' . $join->tablePrimaryKey . ' = ' . $join->fromTableForeignKey;
			}
		}
		
		if(!empty($this->where)) {
			$query .= ' WHERE ';
			$first = true;
			foreach($this->where as $clause) {
				if(!$first) { $query .= ' AND '; } else { $first = false; }
				//we're not binding here becuase we haven't decided how that will work
				$query .= $clause->column . ' ' . $clause->operator . ' ' . $clause->getQueryParameter();
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
	
	public function getWhereArguments() {
		return $this->where;
	}
	
	public function from($table) { 
		foreach($this->where as $criterion) {
			if(strpos($criterion->column, '.') === false) {
				$criterion->column = $this->from . '.' . $criterion->column;
			}
		}
		
		$this->from = $table; 
	
		return $this; 
	}
	
	public function leftOuterJoin($table, $tablePrimaryKey, $fromTableForeignKey) {
		$this->select = $this->from . '.*';
		$this->joins[] = new Join(Join::LEFT, Join::OUTER, $table, $tablePrimaryKey, $fromTableForeignKey);
	}
	
	public function innerJoin($table, $tablePrimaryKey, $fromTableForeignKey) {
		$this->select = $this->from . '.*';
		$this->joins[] = new Join('', Join::INNER, $table, $tablePrimaryKey, $fromTableForeignKey);
	}
	
	public function key($column) { $this->key = $column; }
	
	public function count() { $this->select = "COUNT(*)"; return $this; }
	
	// Criteria
	public function equal($column, $value)       { return $this->where($column, $value, Criterion::EQUAL_TO); }
	public function notEqual($column, $value)    { return $this->where($column, $value, Criterion::NOT_EQUAL_TO); }
	public function between ($column, $lhs, $rhs) { $this->greaterThan($column, $lhs); return $this->lessThan($column, $rhs); }
	public function greaterThan($column, $value)          { return $this->where($column, $value, Criterion::GREATER_THAN); }
	public function greaterThanOrEqualTo($column, $value)         { return $this->where($column, $value, Criterion::GREATER_THAN_EQUAL_TO); }
	public function lessThan($column, $value)          { return $this->where($column, $value, Criterion::LESS_THAN); }
	public function lessThanOrEqualTo($column, $value)         { return $this->where($column, $value, Criterion::LESS_THAN_EQUAL_TO); }
	public function like($column, $value)        { return $this->where($column, $value, Criterion::LIKE); }
	
	protected function where($column, $value, $operator) {
		if(isset($this->from) && strpos($column,'.') === false) {
			$this->where[] = new Criterion($this->from . '.' . $column, $value, $operator); 
		} else {
			$this->where[] = new Criterion($column, $value, $operator);
		}
		return $this;
	}
	
	public function limit($size)           { $this->limit = $size; return $this; }
	public function offset($offset)        { $this->offset = $offset; return $this; }
	public function range($start, $finish) { $this->offset = $start; $this->limit = $finish - $start; return $this; }
	
	public function orderBy($clause) {
		if(isset($this->from) && strpos($clause,'.') === false) {
			$this->orderBy[] = $this->from . '.' . $clause; 
		} else {
			$this->orderBy[] = $clause;
		}
		return $this; 
	}
}
?>