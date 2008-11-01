<?php

Library::import('recess.sources.db.orm.relationships.HasManyRelationship');

class OrmClassInfo {
	public $source;
	public $class;
	public $table;
	public $primaryKey;
	public $columns;
	public $relationships;
	
	public function __construct($class) {
		$this->class = $class;
		$this->table = Inflector::toPlural(Inflector::toUnderscores($class));
		$this->relationships = array();
		$this->source = DbSources::getDefaultSource();
		$this->columns = $this->source->getColumns($this->table);
		$this->primaryKey = $this->table . '.id';
 
		Library::import('recess.lang.RecessReflectionClass');
		try {
			$reflection = new RecessReflectionClass($class);
		} catch(ReflectionException $e) {
			print_r(debug_backtrace());
			throw new RecessException('Class "' . $class . '" has not been declared.', get_defined_vars());
		}
		$annotations = $reflection->getAnnotations();
		foreach($annotations as $annotation) {
			if(is_a($annotation, 'HasManyAnnotation')) {
				$relationship = new HasManyRelationship();
				$relationship->fromAnnotationForClass($annotation, $class);
				$this->relationships[$relationship->name] = $relationship;
			}
		}
	}
}

?>