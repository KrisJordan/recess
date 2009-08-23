<?php
/**
 * A Recess Relationship is an abstraction of a foreign key relationship on the RDBMS.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
abstract class Relationship {
	const FOREIGN_KEY = 'key';
	const FOREIGN_CLASS = 'class';
	const THROUGH = 'through';
	const ON_DELETE = 'ondelete';
	
	const UNSPECIFIED = 'unspecified';
	const CASCADE = 'cascade';
	const DELETE = 'delete';
	const NULLIFY = 'nullify';
	
	public $name;
	public $localClass;
	public $foreignClass;
	public $foreignKey;
	public $onDelete;
	public $through;
	
	abstract function getType();
	
	abstract function init($modelClassName, $relationshipName);
	
	function getDefaultOnDeleteMode() { return Relationship::NULLIFY; }
	
	function delete(Model $model) {
		if($this->onDelete == Relationship::UNSPECIFIED) {
			$this->onDelete = $this->getDefaultOnDeleteMode();
		}
		
		switch($this->onDelete) {
			case Relationship::CASCADE:
				$this->onDeleteCascade($model);
				break;
			case Relationship::DELETE:
				$this->onDeleteDelete($model);
				break;
			case Relationship::NULLIFY:
				$this->onDeleteNullify($model);
				break;	
		}
	}
	
	abstract function onDeleteCascade(Model $model);
	abstract function onDeleteDelete(Model $model);
	abstract function onDeleteNullify(Model $model);
}

?>