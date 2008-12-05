<?php
Library::import('recess.lang.Inflector');
Library::import('recess.lang.RecessObject');
Library::import('recess.lang.RecessReflectionClass');
Library::import('recess.lang.Annotation');

Library::import('recess.sources.db.DbSources');
Library::import('recess.sources.db.sql.ISqlConditions');
Library::import('recess.sources.db.orm.ModelClassInfo');
Library::import('recess.sources.db.sql.SqlBuilder');

Library::import('recess.sources.db.orm.annotations.HasManyAnnotation', true);
Library::import('recess.sources.db.orm.annotations.BelongsToAnnotation', true);
Library::import('recess.sources.db.orm.annotations.TableAnnotation', true);
Library::import('recess.sources.db.orm.annotations.PrimaryKeyAnnotation', true);
Library::import('recess.sources.db.orm.annotations.ForeignKeyAnnotation', true);
Library::import('recess.sources.db.orm.annotations.TypeAnnotation', true);
Library::import('recess.sources.db.orm.annotations.SourceAnnotation', true);

Library::import('recess.sources.db.orm.relationships.Relationship');
Library::import('recess.sources.db.orm.relationships.HasManyRelationship');
Library::import('recess.sources.db.orm.relationships.BelongsToRelationship');

abstract class Model extends RecessObject implements ISqlConditions {
	
	static function sourceFor($class) {
		return self::getClassDescriptor($class)->getSource();
	}
	
	static function sourceNameFor($class) {
		return self::getClassDescriptor($class)->getSourceName();
	}
	
	static function tableFor($class) {
		return self::getClassDescriptor($class)->getTable();
	}
	
	static function primaryKeyFor($class) {
		$descriptor = self::getClassDescriptor($class);
		return $descriptor->getTable() . '.' . $descriptor->primaryKey;
	}
	
	static function primaryKeyName($class) {
		return self::getClassDescriptor($class)->primaryKey;
	}
	
	static function getRelationship($classOrInstance, $name) {
		if(isset(self::getClassDescriptor($classOrInstance)->relationships[$name])) {
			return self::getClassDescriptor($classOrInstance)->relationships[$name];
		} else {
			return false;
		}
	}
	
	static function getRelationships($classOrInstance) {
		return self::getClassDescriptor($classOrInstance)->relationships;
	}
	
	static function getColumns($classOrInstance) {
		return self::getClassDescriptor($classOrInstance)->columns;
	}
	
	static function getProperties($classOrInstance) {
		return self::getClassDescriptor($classOrInstance)->properties;
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
		
		$reflectedProperties = $reflection->getProperties();
		$properties = array();
		foreach($reflectedProperties as $reflectedProperty) {
			if(!$reflectedProperty->isStatic() && $reflectedProperty->isPublic()) {
				$property = new ModelProperty();
				$property->name = $reflectedProperty->name;
				$annotations = $reflectedProperty->getAnnotations();
				foreach($annotations as $annotation) {
					if($annotation instanceof ModelPropertyAnnotation) {
						$annotation->massage($property);
					}
					if($annotation instanceof PrimaryKeyAnnotation) {
						$descriptor->primaryKey = $reflectedProperty->name;
					}
				}
				$properties[] = $property;
			}
		}
		$descriptor->properties = $properties;
		
		return $descriptor;
	}
	
	function all() { 
		return $this->getModelSet()->useAssignmentsAsConditions(false);
	}

	protected function getModelSet() {
		$thisClassDescriptor = self::getClassDescriptor($this);
		$result = $thisClassDescriptor->getSource()->selectModelSet($thisClassDescriptor->getTable());
		foreach($this as $column => $value) {
			if(isset($this->$column) && in_array($column,$thisClassDescriptor->columns)) {
				$result = $result->assign($column, $value);
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
		$sqlBuilder->from($descriptor->getTable());
		foreach($this as $column => $value) {
			if($excludePrimaryKey && $descriptor->primaryKey == $column) continue;
			if(in_array($column, $descriptor->columns) && isset($value)) {
				if($useAssignment) {
					$sqlBuilder->assign($column,$value);
				} else {
					$sqlBuilder->equal($column,$value);
				}
			}
		}
		return $sqlBuilder;
	}
	
	function delete($cascade = true) {	
		$thisClassDescriptor = self::getClassDescriptor($this);
		
		if($cascade) {
			foreach($thisClassDescriptor->relationships as $relationship) {
				$relationship->delete($this);
			}
		}
			
		$sqlBuilder = $this->assignmentSqlForThisObject($thisClassDescriptor, false);
		
		return $thisClassDescriptor->getSource()->executeStatement($sqlBuilder->delete(), $sqlBuilder->getPdoArguments());	
	}
	
	function insert() {
		$thisClassDescriptor = self::getClassDescriptor($this);
		
		$sqlBuilder = $this->assignmentSqlForThisObject($thisClassDescriptor);
		
	 	$result = $thisClassDescriptor->getSource()->executeStatement($sqlBuilder->insert(), $sqlBuilder->getPdoArguments());
	 	
	 	$primaryKey = $thisClassDescriptor->primaryKey;
	 	$this->$primaryKey = $thisClassDescriptor->getSource()->lastInsertId();
	 	
	 	return $result;
	}
	
	function update() {
		$thisClassDescriptor = self::getClassDescriptor($this);
		
		$sqlBuilder = $this->assignmentSqlForThisObject($thisClassDescriptor, true, true);
		$primaryKey = $thisClassDescriptor->primaryKey;
		$sqlBuilder->equal($thisClassDescriptor->primaryKey, $this->$primaryKey);
		
		return $thisClassDescriptor->getSource()->executeStatement($sqlBuilder->update(), $sqlBuilder->getPdoArguments());
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
	
	function exists() {
		$result = $this->find()->first();
		if($result != null) {
			$this->copy($result);
			return true;
		} else {
			return false;
		}
	}
	
	function equal($lhs, $rhs){ return $this->select()->equal($lhs,$rhs); }
	function notEqual($lhs, $rhs) { return $this->select()->notEqual($lhs,$rhs); }
	function between ($column, $lhs, $rhs) { return $this->select()->between($column, $lhs, $hrs); }
	function greaterThan($lhs, $rhs) { return $this->select()->greaterThan($lhs,$rhs); }
	function greaterThanOrEqualTo($lhs, $rhs) { return $this->select()->greaterThanOrEqualTo($lhs,$rhs); }
	function lessThan($lhs, $rhs) { return $this->select()->lessThan($lhs,$rhs); }
	function lessThanOrEqualTo($lhs, $rhs) { return $this->select()->lessThanOrEqualTo($lhs,$rhs); }
	function like($lhs, $rhs) { return $this->select()->like($lhs,$rhs); }
	
	function copy($keyValuePair) {
		foreach($keyValuePair as $key => $value) {
			$this->$key = $value;
		}
		return $this;
	}
}

class ModelProperty {
	public $name;
	public $type;
	public $pkCallback;
	public $autoincrement = false;
	public $isPrimaryKey = false;
	public $isForeignKey = false;
	public $required = false;
	
	function __set_state($array) {
		$prop = new ModelProperty();
		$prop->name = $array['name'];
		$prop->type = $array['type'];
		$prop->pkCallback = $array['pkCallback'];
		$prop->autoincrement = $array['autoincrement'];
		$prop->isPrimaryKey = $array['isPrimaryKey'];
		$prop->isForeignKey = $array['isForeignKey'];
		return $prop;
	}
}

class ModelDescriptor extends RecessObjectDescriptor {
	public $primaryKey = 'id';
	private $table;
	
	public $modelClass;
	public $relationships;
	
	public $columns;
	public $properties;
	
	public $source;
	
	function __construct($class, $loadColumns = true) {
		$this->table = Inflector::toPlural(Inflector::toUnderscores($class));
		$this->relationships = array();
		$this->properties = array();
		$this->source = false;
		if($loadColumns) {
			$this->columns = $this->getSource()->getColumns($this->table);
		} else {
			$this->columns = array();
		}
		$this->primaryKeyColumn = 'id';
		$this->modelClass = $class;
	}
	
	function __set_state($array) {
		$descriptor = new ModelDescriptor($array['modelClass']);
		$descriptor->primaryKey = $array['primaryKey'];
		$descriptor->table = $array['table'];
		$descriptor->relationships = $array['relationships'];
		$descriptor->columns = $array['columns'];
		$descriptor->properties = $array['properties'];
		$descriptor->source = $array['source'];
		$descriptor->attachedMethods = $array['attachedMethods'];
		return $descriptor;
	}
	
	function setTable($table, $loadColumns = true) {
		$this->table = $table;
		if($loadColumns) {
			$this->columns = $this->getSource()->getColumns($this->table);
		} else {
			$this->columns = array();
		}
	}
	
	function getTable() {
		return $this->table;
	}
	
	function setSource($source) {
		$this->source = $source;		
	}
	
	function getSource() {
		if(!$this->source) {
			return DbSources::getDefaultSource();
		} else {
			return DbSources::getSource($this->source);
		}
	}
	
	function getSourceName() {
		if(!$this->source) {
			return 'Default';
		} else {
			return $this->source;
		}
	}
}
?>