<?php
Library::import('recess.database.orm.annotations.ModelAnnotation');
Library::import('recess.database.orm.relationships.HasManyRelationship');

/**
 * An annotation used on Model Classes, the HasMany annotations gives a model
 * a HasManyRelationship.
 * 
 * @author Kris Jordan
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link http://www.recessframework.org/
 */
class HasManyAnnotation extends ModelAnnotation {
	protected $relationshipName;
	protected $settings = array();
	
	function init($array) {
		if(count($array) < 1) {
			throw new RecessException('HasMany annotation requires at least a name: /** HasMany nameOfRelationship */');
		}
		$this->relationshipName = array_shift($array);
		$this->settings = $array;
	}
	
	function massage(ModelDescriptor &$descriptor) {
		$relationship = new HasManyRelationship();
		$this->massageRelationshipHelper($descriptor, $relationship);
	}

}
?>