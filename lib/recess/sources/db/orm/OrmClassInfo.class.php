<?php

Library::import('recess.sources.db.orm.relationships.HasManyRelationship');
Library::import('recess.sources.db.orm.relationships.BelongsToRelationship');
Library::import('recess.sources.db.orm.relationships.HasAndBelongsToManyRelationship');

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
					$this->table = $annotation->table;
					unset($relationship);
					break;
			}
			if(!isset($relationship)) continue;
			
			$relationship->fromAnnotationForClass($annotation, $class);
			$this->relationships[$relationship->name] = $relationship;
		}
	}
}

?>