<?php
Library::import('recess.lang.Inflector');
Library::import('recess.lang.RecessClass');
Library::import('recess.lang.RecessReflectionClass');

Library::import('recess.sources.db.DbSources');
Library::import('recess.sources.db.sql.ISqlConditions');
Library::import('recess.sources.db.orm.ModelClassInfo');

Library::import('recess.sources.db.orm.annotations.HasManyAnnotation', true);
Library::import('recess.sources.db.orm.annotations.BelongsToAnnotation', true);
Library::import('recess.sources.db.orm.annotations.HasAndBelongsToManyAnnotation', true);
Library::import('recess.sources.db.orm.annotations.TableAnnotation', true);
Library::import('recess.sources.db.orm.annotations.PrimaryKeyAnnotation', true);

Library::import('recess.sources.db.orm.relationships.HasManyRelationship');
Library::import('recess.sources.db.orm.relationships.BelongsToRelationship');
Library::import('recess.sources.db.orm.relationships.HasAndBelongsToManyRelationship');

abstract class Model extends RecessClass implements ISqlConditions {
	
	static function sourceFor($class) {
		return self::getClassDescriptor($class)->source;
	}
	
	static function tableFor($class) {
		return self::getClassDescriptor($class)->table;
	}
	
	static function primaryKeyFor($class) {
		$descriptor = self::getClassDescriptor($class);
		return $descriptor->table . '.' . $descriptor->primaryKey;
	}
	
	static function primaryKeyName($class) {
		return self::getClassDescriptor($class)->primaryKey;
	}
	
	static function getRelationship($class, $name) {
		if(isset(self::getClassDescriptor($class)->relationships[$name])) {
			return self::getClassDescriptor($class)->relationships[$name];
		} else {
			return false;
		}
	}
	
	static protected function buildClassDescriptor($class) {
		$descriptor = new ModelDescriptor($class);
		
		try {
			$reflection = new RecessReflectionClass($class);
		} catch(ReflectionException $e) {
			throw new RecessException('Class "' . $class . '" has not been declared.', get_defined_vars());
		}
		
		$annotations = $reflection->getAnnotations();
		foreach($annotations as $annotation) {
			if($annotation instanceof ModelAnnotation) {
				$annotation->massage($descriptor);
			}
		}
		
		return $descriptor;
	}
	
	function all() { 
		return $this->getModelSet()->useAssignmentsAsConditions(false);
	}

	protected function getModelSet() {
		$thisClassDescriptor = self::getClassDescriptor($this);
		$result = $thisClassDescriptor->source->selectModelSet($thisClassDescriptor->table);
		foreach($this as $column => $value) {
			if(in_array($column,$thisClassDescriptor->columns)) {
				$result->assign($column, $value);
			}
		}
		$result->rowClass = get_class($this);
		return $result;
	}
	
	function select() { 
		return $this->getModelSet()->useAssignmentsAsConditions(true);
	}
	
	protected function assignmentSqlForThisObject(ModelDescriptor $descriptor, $useAssignment = true, $excludePrimaryKey = false) {
		$sqlBuilder = new SqlBuilder();
		$sqlBuilder->from($descriptor->table);
		foreach($this as $column => $value) {
			if($excludePrimaryKey && $descriptor->primaryKey == $column) continue;
			if(in_array($column, $descriptor->columns)) {
				if($useAssignment)
					$sqlBuilder->assign($column,$value);
				else
					$sqlBuilder->equal($column,$value);
			}
		}
		return $sqlBuilder;
	}
	
	function delete($cascade = true) {
		if(!isset($this->inDeleteState)) 
			$this->inDeleteState = true;
		else {
			echo 'Circularity!<br />';
			return;
		}
		
		$thisClassDescriptor = self::getClassDescriptor($this);
		
		if($cascade) {
			foreach($thisClassDescriptor->relationships as $relationship) {
				$relationship->delete($this);
			}
		}
		
		$sqlBuilder = $this->assignmentSqlForThisObject($thisClassDescriptor, false);
		
		return $thisClassDescriptor->source->executeStatement($sqlBuilder->delete(), $sqlBuilder->getPdoArguments());	
	}
	
	function insert() {
		$thisClassDescriptor = self::getClassDescriptor($this);
		
		$sqlBuilder = $this->assignmentSqlForThisObject($thisClassDescriptor);
		
	 	$result = $thisClassDescriptor->source->executeStatement($sqlBuilder->insert(), $sqlBuilder->getPdoArguments());
	 	
	 	$primaryKey = $thisClassDescriptor->primaryKey;
	 	$this->$primaryKey = $thisClassDescriptor->source->lastInsertId();
	 	
	 	return $result;
	}
	
	function update() {
		$thisClassDescriptor = self::getClassDescriptor($this);
		
		$sqlBuilder = $this->assignmentSqlForThisObject($thisClassDescriptor, true, true);
		$primaryKey = $thisClassDescriptor->primaryKey;
		$sqlBuilder->equal($thisClassDescriptor->primaryKey, $this->$primaryKey);
		
		return $thisClassDescriptor->source->executeStatement($sqlBuilder->update(), $sqlBuilder->getPdoArguments());
	}
	
	function save()   {
		if($this->primaryKeyIsSet()) {
			return $this->update();
		} else {
			return $this->insert();
		}
	}
	
	function primaryKeyIsSet() {
		$thisClassDescriptor = self::getClassDescriptor($this);
		
		$primaryKey = $thisClassDescriptor->primaryKey;
		
		if(isset($this->$primaryKey)) {
			return true;
		} else {
			return false;
		}
	}
	
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

class ModelDescriptor extends RecessClassDescriptor {
	public $primaryKey = 'id';
	public $table;
	
	public $modelClass;
	public $relationships;
	public $columns;
	
	// TODO: This will need to be refactored to reference the source_name verses the source
	public $source_name;
	public $source;
	
	function __construct($class) {
		$this->table = Inflector::toPlural(Inflector::toUnderscores($class));
		$this->relationships = array();
		$this->source = DbSources::getDefaultSource();
		$this->columns = $this->source->getColumns($this->table);
		$this->primaryKeyColumn = 'id';
		$this->modelClass = $class;
	}
}
?>