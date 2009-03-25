<?php
Library::import('recess.database.orm.annotations.AbstractRelationshipAnnotation');
Library::import('recess.database.orm.relationships.HasManyRelationship');

/**
 * An annotation used on Model Classes, the HasMany annotations gives a model
 * a HasManyRelationship.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class HasManyAnnotation extends AbstractRelationshipAnnotation {
	
	static $ACCEPTED_KEYS = array(Relationship::FOREIGN_CLASS, Relationship::FOREIGN_KEY, Relationship::THROUGH, Relationship::ON_DELETE);	

	public function usage() {
		return '!HasMany relationshipName [, Class: relatedClass] [, Key: foreignKey] [, Through: throughClass ] [, OnDelete: ( Delete | Cascade | Nullify )]';
	}
	
	protected function validate($class) {
		$this->minimumParameterCount(1);
		$this->maximumParameterCount(5);
		$this->acceptedKeys(self::$ACCEPTED_KEYS);
		$this->acceptedValuesForKey(Relationship::ON_DELETE, parent::$ON_DELETE_VALUES, CASE_LOWER);
	}
	
	protected function expand($class, $reflection, $descriptor) {
		$relationshipName = $this->values[0];
		
		$relationship = new HasManyRelationship();
		$relationship->init($class, $relationshipName);
		
		$this->expandHelper($relationship, $descriptor);
	}

}
?>