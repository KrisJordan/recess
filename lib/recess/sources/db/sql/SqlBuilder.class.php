<?php

Library::import('recess.sources.db.sql.ISqlConditions');
Library::import('recess.sources.db.sql.ISqlSelectOptions');
Library::import('recess.sources.db.sql.Criterion');
Library::import('recess.sources.db.sql.Join');

class SqlBuilder implements ISqlConditions, ISqlSelectOptions {
	
	/* INSERT */
	protected $table;
	protected $assignments = array();
	
	public function insert() {
		$this->insertSanityCheck();

		$sql = 'INSERT INTO ' . $this->table;
		
		$columns = '';
		$values = '';
		$first = true;
		$table_prefix = $this->table . '.';
		foreach($this->assignments as $assignment) {
			if($first) { $first = false; }
			else { $columns .= ', '; $values .= ', '; }
			$columns .= str_replace($table_prefix, '', $assignment->column);
			$values .= $assignment->getQueryParameter();
		}
		$columns = ' (' . $columns . ')';
		$values = '(' . $values . ')';
		
		$sql .= $columns . ' VALUES ' . $values;
		
		return $sql;
	}
	
	protected function insertSanityCheck() {
		if(	!empty($this->conditions) )
			throw new RecessException('Insert does not use conditionals.', get_defined_vars());
		if(	!empty($this->joins) )
			throw new RecessException('Insert does not use joins.', get_defined_vars());
		if(	!empty($this->orderBy) ) 
			throw new RecessException('Insert does not use order by.', get_defined_vars());
		if(	isset($this->limit) )
			throw new RecessException('Insert does not use limit.', get_defined_vars());
		if(	isset($this->offset) )
			throw new RecessException('Insert does not use offset.', get_defined_vars());
		if(	isset($this->distinct) )
			throw new RecessException('Insert does not use distinct.', get_defined_vars());
	}
	
	public function table($table) { $this->table = $table; return $this; }
	public function into($table) { return $this->table($table); }

	public function assign($column, $value) { 
		if(strpos($column, '.') === false) {
			if(isset($this->table)) {
				$this->assignments[] = new Criterion($this->table . '.' . $column, $value, Criterion::ASSIGNMENT); 
			} else {
				throw new RecessException('Cannot assign without specifying table.', get_defined_vars());
			}
		} else {
			$this->assignments[] = new Criterion($column, $value, Criterion::ASSIGNMENT); 
		}
	}
	
	/* UPDATE & DELETE */
	protected $conditions = array();
	protected $useAssignmentsAsConditions = false;
	
	public function delete() {
		$this->deleteSanityCheck();
		return 'DELETE FROM ' . $this->table . $this->whereHelper();
	}
	protected function deleteSanityCheck() {
		if(	!empty($this->joins) )
			throw new RecessException('Delete does not use joins.', get_defined_vars());
		if(	!empty($this->orderBy) ) 
			throw new RecessException('Delete does not use order by.', get_defined_vars());
		if(	isset($this->limit) )
			throw new RecessException('Delete does not use limit.', get_defined_vars());
		if(	isset($this->offset) )
			throw new RecessException('Delete does not use offset.', get_defined_vars());
		if(	isset($this->distinct) )
			throw new RecessException('Delete does not use distinct.', get_defined_vars());
		if( !empty($this->assignments) && !$this->useAssignmentsAsConditions)
			throw new RecessException('Delete does not use assignments. To use assignments as conditions add ->useAssignmentsAsConditions() to your method call chain.', get_defined_vars());
	}
	
	public function update() {
		$this->updateSanityCheck();
		$sql = 'UPDATE ' . $this->table . ' SET ';
		
		$first = true;
		$table_prefix = $this->table . '.';
		foreach($this->assignments as $assignment) {
			if($first) { $first = false; }
			else { $sql .= ', '; }
			$sql .= str_replace($table_prefix, '', $assignment->column) . ' = ' . $assignment->getQueryParameter();
		}
		
		$sql .= $this->whereHelper();
		
		return $sql;
	}
	protected function updateSanityCheck() {
		if(	!empty($this->joins) )
			throw new RecessException('Update does not use joins.', get_defined_vars());
		if(	!empty($this->orderBy) ) 
			throw new RecessException('Update (in Recess!) does not use order by.', get_defined_vars());
		if(	isset($this->limit) )
			throw new RecessException('Update (in Recess!) does not use limit.', get_defined_vars());
		if(	isset($this->offset) )
			throw new RecessException('Update (in Recess!) does not use offset.', get_defined_vars());
		if(	isset($this->distinct) )
			throw new RecessException('Update does not use distinct.', get_defined_vars());
	}
	
	public function getPdoArguments() {
		return array_merge($this->conditions, $this->assignments);
	}
	
	public function from($table) { return $this->table($table); }
	public function useAssignmentsAsConditions($bool) { $this->useAssignmentsAsConditions = $bool; return $this; }
	
	/* ISqlConditions */
	public function equal($column, $value)       { return $this->addCondition($column, $value, Criterion::EQUAL_TO); }
	public function notEqual($column, $value)    { return $this->addCondition($column, $value, Criterion::NOT_EQUAL_TO); }
	public function between ($column, $small, $big) { $this->greaterThan($column, $small); return $this->lessThan($column, $big); }
	public function greaterThan($column, $value)          { return $this->addCondition($column, $value, Criterion::GREATER_THAN); }
	public function greaterThanOrEqualTo($column, $value)         { return $this->addCondition($column, $value, Criterion::GREATER_THAN_EQUAL_TO); }
	public function lessThan($column, $value)          { return $this->addCondition($column, $value, Criterion::LESS_THAN); }
	public function lessThanOrEqualTo($column, $value)         { return $this->addCondition($column, $value, Criterion::LESS_THAN_EQUAL_TO); }
	public function like($column, $value)        { return $this->addCondition($column, $value, Criterion::LIKE); }
	
	protected function addCondition($column, $value, $operator) {
		if(strpos($column, '.') === false) {
			if(isset($this->table)) {
				$this->conditions[] = new Criterion($this->table . '.' . $column, $value, $operator); 
			} else {
				throw new RecessException('Cannot use "' . $operator . '" operator without specifying table for column "' . $column . '".', get_defined_vars());
			}
		} else {
			$this->conditions[] = new Criterion($column, $value, $operator);
		}
		return $this;
	}
	
	/* SELECT */
	protected $select = '*';
	protected $joins = array();
	protected $limit;
	protected $offset;
	protected $distinct;
	protected $orderBy = array();
	
	public function select() {
		$this->selectSanityCheck();

		$sql = 'SELECT ' . $this->distinct . $this->select . ' FROM ' . $this->table;
		
		$sql .= $this->joinHelper();
		
		$sql .= $this->whereHelper();
		
		$sql .= $this->orderByHelper();
		
		$sql .= $this->rangeHelper();
		
		return $sql;
	}

	protected function selectSanityCheck() {
		if( (!empty($this->where) || !empty($this->orderBy)) && !isset($this->table))
			throw new RecessException('Must have from if using where.', get_defined_vars());
		
		if( isset($this->offset) && !isset($this->limit))
			throw new RecessException('Must define limit if using offset.', get_defined_vars());
		
		if($this->select == '*' && !isset($this->table))
			throw new RecessException('No table has been selected.', get_defined_vars());
	}
	
	/* ISqlSelectOptions */
	
	public function limit($size)           { $this->limit = $size; return $this; }
	public function offset($offset)        { $this->offset = $offset; return $this; }
	public function range($start, $finish) { $this->offset = $start; $this->limit = $finish - $start; return $this; }
	
	public function orderBy($clause) {
		if(isset($this->table) && strpos($clause,'.') === false) {
			$this->orderBy[] = $this->table . '.' . $clause; 
		} else {
			$this->orderBy[] = $clause;
		}
		return $this; 
	}
	
	public function leftOuterJoin($table, $tablePrimaryKey, $fromTableForeignKey) {
		return $this->join(Join::LEFT, Join::OUTER, $table, $tablePrimaryKey, $fromTableForeignKey);
	}
	
	public function innerJoin($table, $tablePrimaryKey, $fromTableForeignKey) {
		return $this->join('', Join::INNER, $table, $tablePrimaryKey, $fromTableForeignKey);
	}
	protected function join($leftOrRight, $innerOrOuter, $table, $tablePrimaryKey, $fromTableForeignKey) {
		$this->select = $this->table . '.*';
		$this->joins[] = new Join($leftOrRight, $innerOrOuter, $table, $tablePrimaryKey, $fromTableForeignKey);	
		return $this;
	}
	
	public function distinct() { $this->distinct = ' DISTINCT '; return $this; }

	/* HELPER METHODS */
	protected function whereHelper() {
		$sql = '';
		
		$first = true;
		if(!empty($this->conditions)) {
			foreach($this->conditions as $clause) {
				if(!$first) { $sql .= ' AND '; } else { $first = false; } // TODO: Figure out how we'll do ORing
				$sql .= $clause->column . ' ' . $clause->operator . ' ' . $clause->getQueryParameter();
			}
		}
		
		if($this->useAssignmentsAsConditions && !empty($this->assignments)) {
			foreach($this->assignments as $clause) {
				if(!$first) { $sql .= ' AND '; } else { $first = false; } // TODO: Figure out how we'll do ORing
				$sql .= $clause->column . ' == ' . $clause->getQueryParameter();
			}
		}
		
		if($sql != '') {
			$sql = ' WHERE ' . $sql;
		}
		
		return $sql;
	}
	
	protected function joinHelper() {
		$sql = '';
		if(!empty($this->joins)) {
			foreach($this->joins as $join) {
				$joinStatement = '';
				
				if(isset($join->natural)) {
					$joinStatement .= $join->natural . ' ';
				}
				if(isset($join->leftRightOrFull)) {
					$joinStatement .= $join->leftRightOrFull . ' ';
				}
				if(isset($join->innerOuterOrCross)) {
					$joinStatement .= $join->innerOuterOrCross . ' ';
				}
				
				$onStatement = ' ON ' . $join->tablePrimaryKey . ' = ' . $join->fromTableForeignKey;
				$joinStatement .= 'JOIN ' . $join->table . $onStatement;
				
				$sql .= $joinStatement;
			}
		}
		return $sql;
	}
	
	protected function orderByHelper() {
		$sql = '';
		if(!empty($this->orderBy)){
			$sql = ' ORDER BY ';
			$first = true;
			foreach($this->orderBy as $order){
				if(!$first) { $sql .= ', '; } else { $first = false; }
				$sql .= $order;
			}
		}
		return $sql;
	}
	
	protected function rangeHelper() {
		$sql = '';
		if(isset($this->limit)){ $sql .= ' LIMIT ' . $this->limit; }
		if(isset($this->offset)){ $sql .= ' OFFSET ' . $this->offset; }
		return $sql;
	}
}

?>