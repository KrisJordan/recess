<?php
Library::import('recess.utility.Inflector');
Library::import('recess.sources.db.DbSources');

abstract class ModelBase extends stdClass {
	
	protected static $ormInfo = array();
	
	public function find() {
		$thisOrm = self::ormInfoFor($this);
		$result = $thisOrm->source->select($thisOrm->table);
		foreach($this as $column => $value) {
			if(in_array($column,$thisOrm->columns)) {
				$result->equal($column, $value);
			}
		}
		$result->rowClass = $thisOrm->class; // TODO: make setting rowClass a method call?
		return $result;
	}
	
	public function __call($name, $args) {
		return new stdClass();
	}
		
	protected static function ormInfoFor($object) {
		$class = get_class($object);
		if(!isset(self::$ormInfo[$class])) {
			self::$ormInfo[$class] = new ClassOrmInfo($class);
		}
		return self::$ormInfo[$class];
	}
}

class ClassOrmInfo {
	public $source;
	public $class;
	public $table;
	public $columns;
	public $relationships;
	
	public function __construct($class) {
		$this->class = $class;
		$this->table = Inflector::toPlural(Inflector::toUnderscores($class));
		$this->relationships = array();
		$this->source = DbSources::getDefaultSource();
		$this->columns = $this->source->getColumns($this->table);
// Todo
//		Library::import('recess.lang.RecessReflectionClass');
//		$reflection = new RecessReflectionClass($class);
//		$annotations = $reflection->getAnnotations();
//		foreach($annotations as $annotation) {
//			if(is_a($annotation, 'HasManyAnnotation')) {
//				$relationship = new Relationship();
//				$relationship->name = $annotation->name;
//			}
//		}
	}
}

class Relationship {
	public $name;
	public $class;
}


?>