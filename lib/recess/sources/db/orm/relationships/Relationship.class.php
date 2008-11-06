<?php

abstract class Relationship {
	const FOREIGN_KEY = 'foreignkey';
	const FOREIGN_CLASS = 'class';
	const THROUGH = 'through';
	const ON_DELETE = 'ondelete';
	
	const CASCADE = 'cascade';
	const DELETE = 'delete';
	const NULLIFY = 'nullify';
	
	public $name;
	public $localClass;
	public $foreignClass;
	public $foreignKey;
	public $onDelete;
	
	abstract function init($modelClassName, $relationshipName);
	
	function delete(Model $model) {
		echo $this->onDelete . ' ' . get_class($model) . '->' . $this->name . '<br />';
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