<?php

class ModelConventions {
	
	// To provide your own naming conventions override these methods:
	
	static final function setConventions(ModelConventions $instance) {
		self::$instance = $instance;
	}
	
	protected function myTableNameFromClass($class) {
		return strtolower($class);
	}
	
	protected function myRelatedClassFromBelongsToName($belongsToName) {
		return ucfirst($belongsToName);
	}
	
	protected function myRelatedForeignKeyFromBelongsToName($belongsToName) {
		return $belongsToName . 'Id';
	}
	
	protected function myRelatedClassFromHasManyName($hasManyName) {
		return ucfirst($hasManyName);
	}
	
	protected function myRelatedForeignKeyFromHasManyModelName($modelName) {
		if($modelName != '') {
			$modelName[0] = strtolower($modelName[0]);
		}
		return $modelName . 'Id';
	}
	
	// End overrideable methods.
	
	protected $instance;
	
	protected function __construct() {}
	
	static final function init() {
		self::$instance = new ModelConventions();
	}
	
	static final function tableNameFromClass($class) {
		return self::$instance->myTableNameFromClass($class);
	}
	
	static final function relatedClassFromBelongsToName($belongsToName) {
		return self::$instance->myRelatedClassFromBelongsToName($belongsToName);
	}
	
	static final function relatedForeignKeyFromBelongsToName($belongsToName) {
		return self::$instance->myRelatedForeignKeyFromBelongsToName($belongsToName);
	}
	
	static final function relatedClassFromHasManyName($hasManyName) {
		return self::$instance->myRelatedClassFromHasManyName($hasManyName);
	}
	
	static final function relatedForeignKeyFromHasManyClass($class) {
		return self::$instance->myRelatedForeignKeyFromHasManyClass($class);
	}
	


	
}
ModelConventions::init();

?>