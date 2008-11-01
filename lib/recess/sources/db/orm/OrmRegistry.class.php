<?php
Library::import('recess.sources.db.orm.OrmClassInfo');

abstract class OrmRegistry {
	
	protected static $registry = array();
	
	public static function infoForObject($object) {
		$class = get_class($object);
		return self::infoForClass($class);
	}
	
	public static function infoForClass($class) {
		if(!isset(self::$registry[$class])) {
			self::$registry[$class] = new OrmClassInfo($class);
		}
		return self::$registry[$class];
	}
	
	public static function tableFor($class) {
		$info = self::infoForClass($class);
		return $info->table;
	}
	
	public static function primaryKeyFor($class) {
		$info = self::infoForClass($class);
		return $info->primaryKey;
	}
	
}
?>