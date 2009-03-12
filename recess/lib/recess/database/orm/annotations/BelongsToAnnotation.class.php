<?php
Library::import('recess.database.orm.annotations.AbstractRelationshipAnnotation');
Library::import('recess.database.orm.relationships.BelongsToRelationship');

/**
 * An annotation used on Model Classes, the BelongsTo annotation gives a model
 * a BelongsToRelationship.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class BelongsToAnnotation extends AbstractRelationshipAnnotation {
	
	static $ACCEPTED_KEYS = array(Relationship::FOREIGN_CLASS, Relationship::FOREIGN_KEY, Relationship::ON_DELETE);
	
	public function usage() {
		return '!BelongsTo relationshipName [, Class: relatedClass] [, Key: foreignKey] [, OnDelete: ( Nullify | Cascade | Delete )]';
	}
	
	protected function validate($class) {
		$this->minimumParameterCount(1);
		$this->maximumParameterCount(4);
		$this->acceptedKeys(self::$ACCEPTED_KEYS);
		$this->acceptedValuesForKey(Relationship::ON_DELETE, parent::$ON_DELETE_VALUES, CASE_LOWER);
	}
	
	protected function expand($class, $reflection, $descriptor) {
		$relationshipName = $this->values[0];
		
		$relationship = new BelongsToRelationship();
		$relationship->init($class, $relationshipName);
		
		$this->expandHelper($relationship, $descriptor);
	}

}
?>