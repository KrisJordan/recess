<?php
Library::import('recess.database.orm.annotations');

/**
 * An annotation used on Model Classes, the BelongsTo annotation gives a model
 * a BelongsToRelationship.
 * 
 * @author Kris Jordan
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link http://www.recessframework.org/
 */
class BelongsToAnnotation extends ModelAnnotation {
	protected $relationshipName;
	protected $settings = array();
	
	function init($array) {
		if(count($array) < 1) {
			throw new RecessException('BelongsTo annotation requires at least a name: /** BelongsTo nameOfRelationship */');
		}
		$this->relationshipName = array_shift($array);
		$this->settings = $array;
	}
	
	function massage(ModelDescriptor &$descriptor) {
		$relationship = new BelongsToRelationship();
		$this->massageRelationshipHelper($descriptor, $relationship);
	}

}
?>