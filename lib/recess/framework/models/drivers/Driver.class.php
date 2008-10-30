<?php

abstract class Driver {
	protected $pdo;
	
	public function pdo() { 
		return $this->pdo;
	}
	
	public function __deconstruct() {
		$this->disconnect();
	}
	
	public abstract function connect($dsn);
	public abstract function disconnect();
	
	public abstract function tables();			// Returns array of Tables
	public abstract function columns($table);	// Returns array of Fields
	
	public function beginTransaction() {
		$this->pdo->beginTransaction();
	}
	
	public function commitTransaction() {
		$this->pdo->commit();
	}
	
	public function query(QueryModel $query, $class) {
		$statement = $this->pdo->prepare($query->getSql());
		foreach($query->where as $clause) {
			$statement->bindParam(':'.$clause->lhs,$clause->rhs); //TODO: Name the params in query
		}
		// TODO: Binding on LIMIT, OFFSET
		$statement->execute();
		$results = array();
		while($result = $statement->fetchObject($class)) {
			$results[] = $result;
		}
		return $results;
	}
	
	public function rollbackTransaction() {
		$this->pdo->rollBack();
	}
}

?>