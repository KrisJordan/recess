<?php
Library::import('recess.lang.Annotation');

/**
 * Abstract class for relationship annotations.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
abstract class AbstractRelationshipAnnotation extends Annotation {

	static $ON_DELETE_VALUES = array(Relationship::CASCADE, Relationship::DELETE, Relationship::NULLIFY);
	
	protected $class;
	protected $key;
	protected $through;
	protected $ondelete;
	
	public function isFor() {
		return Annotation::FOR_CLASS;
	}
	
	protected function expandHelper($relationship, $descriptor) {
		$relationshipName = $this->values[0];
		
		if(isset($this->class)) {
			$relationship->foreignClass = $this->class;
		}
		
		if(isset($this->key)) {
			$relationship->foreignKey = $this->key;
		}
		
		if(isset($this->through)) {
			$relationship->through = $this->through;
		}
		
		if(isset($this->ondelete)) {
			$relationship->onDelete = strtolower($this->ondelete);
		}
		
		$descriptor->relationships[$relationshipName] = $relationship;
		
		$relationship->attachMethodsToModelDescriptor($descriptor);
	}

}
?>