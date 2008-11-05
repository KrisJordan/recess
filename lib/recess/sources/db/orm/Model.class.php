<?php
Library::import('recess.lang.Inflector');
Library::import('recess.lang.RecessClass');
Library::import('recess.lang.RecessClassInfo');
Library::import('recess.lang.RecessAttachedMethod');

Library::import('recess.sources.db.DbSources');
Library::import('recess.sources.db.sql.ISqlConditions');
Library::import('recess.sources.db.orm.ModelClassInfo');

Library::import('recess.sources.db.orm.annotations.HasManyAnnotation', true);
Library::import('recess.sources.db.orm.annotations.BelongsToAnnotation', true);
Library::import('recess.sources.db.orm.annotations.HasAndBelongsToManyAnnotation', true);
Library::import('recess.sources.db.orm.annotations.TableAnnotation', true);
Library::import('recess.sources.db.orm.relationships.HasManyRelationship');
Library::import('recess.sources.db.orm.relationships.BelongsToRelationship');
Library::import('recess.sources.db.orm.relationships.HasAndBelongsToManyRelationship');

abstract class Model extends RecessClass implements ISqlConditions {
	
//	public function __call($name, $args) {
//		$thisOrm = OrmRegistry::infoForObject($this);
//		if(isset($thisOrm->relationships[$name])) {
//			return $thisOrm->relationships[$name]->selectModel($this);
//		} else {
//			throw new RecessException('Relationship "' . $name . '" does not exist.', get_defined_vars());
//		}
//	}
	
	static function getRecessClassInfo($class) {
		$modelClassInfo = new ModelClassInfo();
		$modelClassInfo->table = Inflector::toPlural(Inflector::toUnderscores($class));
		$modelClassInfo->relationships = array();
		$modelClassInfo->source = DbSources::getDefaultSource();
		$modelClassInfo->columns = $modelClassInfo->source->getColumns($modelClassInfo->table);
		$modelClassInfo->primaryKey = $modelClassInfo->table . '.id';
		
		$classInfo = new RecessClassInfo();
		$classInfo->modelInfo = $modelClassInfo;
		
		Library::import('recess.lang.RecessReflectionClass');
		try {
			$reflection = new RecessReflectionClass($class);
		} catch(ReflectionException $e) {
			throw new RecessException('Class "' . $class . '" has not been declared.', get_defined_vars());
		}
		$annotations = $reflection->getAnnotations();
		foreach($annotations as $annotation) {
			$annotationClass = get_class($annotation);
			unset($relationship);
			switch($annotationClass) {
				case 'HasManyAnnotation':
					$relationship = new HasManyRelationship();
					break;
				case 'BelongsToAnnotation':
					$relationship = new BelongsToRelationship();
					break;
				case 'HasAndBelongsToManyAnnotation':
					$relationship = new HasAndBelongsToManyRelationship();
					break;
				case 'TableAnnotation':
					$modelClassInfo->table = $annotation->table;
					$modelClassInfo->primaryKey = $modelClassInfo->table . '.id';
					unset($relationship);
					break;
			}
			if(!isset($relationship)) continue;
			
			$relationship->fromAnnotationForClass($annotation, $class);
			$modelClassInfo->relationships[$relationship->name] = $relationship;
		}
		
		// attach methods
		foreach($modelClassInfo->relationships as $name => $relationship) {
			$attachedMethod = new RecessAttachedMethod($relationship,'selectModel');
			$classInfo->addAttachedMethod($name, $attachedMethod);
		}
		
		return $classInfo;
	}
	
	function all() { 
		return $this->getModelSet()->useAssignmentsAsConditions(false);
	}

	protected function getModelSet() {
		$thisOrm = RecessClassRegistry::infoForObject($this)->modelInfo;
		$result = $thisOrm->source->selectModelSet($thisOrm->table);
		foreach($this as $column => $value) {
			if(in_array($column,$thisOrm->columns)) {
				$result->assign($column, $value);
			}
		}
		$result->rowClass = get_class($this);
		return $result;
	}
	
	function select() { 
		return $this->getModelSet()->useAssignmentsAsConditions(true);
	}
	
	protected function assignmentSqlForThisObject(ModelClassInfo $modelClassInfo, $useAssignment = true, $excludePrimaryKey = false) {
		$sqlBuilder = new SqlBuilder();
		$sqlBuilder->from($modelClassInfo->table);
		foreach($this as $column => $value) {
			if($excludePrimaryKey && $modelClassInfo->primaryKey == $column) continue;
			if(in_array($column, $modelClassInfo->columns)) {
				if($useAssignment)
					$sqlBuilder->assign($column,$value);
				else
					$sqlBuilder->equal($column,$value);
			}
		}
		return $sqlBuilder;
	}
	
	function delete() {
		$modelClassInfo = RecessClassRegistry::infoForObject($this)->modelInfo;
		
		$sqlBuilder = $this->assignmentSqlForThisObject($modelClassInfo, false);
		
		return $thisOrm->source->executeStatement($sqlBuilder->delete(), $sqlBuilder->getPdoArguments());	
	}
	
	function insert() {
		$thisOrm = RecessClassRegistry::infoForObject($this)->modelInfo;
		
		$sqlBuilder = $this->assignmentSqlForThisObject($thisOrm);
		
		return $thisOrm->source->executeStatement($sqlBuilder->insert(), $sqlBuilder->getPdoArguments());
	}
	
	function update() {
		$thisOrm = RecessClassRegistry::infoForObject($this)->modelInfo;
		
		$sqlBuilder = $this->assignmentSqlForThisObject($thisOrm, true, true);
		$pk = str_replace($thisOrm->table . '.', '', $thisOrm->primaryKey);
		$sqlBuilder->equal($thisOrm->primaryKey, $this->$pk);
		
		return $thisOrm->source->executeStatement($sqlBuilder->update(), $sqlBuilder->getPdoArguments());
	}
	
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