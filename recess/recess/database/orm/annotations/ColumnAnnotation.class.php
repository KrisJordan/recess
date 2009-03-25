<?php
Library::import('recess.lang.Annotation');
Library::import('recess.database.pdo.RecessType');

/**
 * An annotation used on Model properties which specifies information about the column
 * a given property maps to in the data source.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class ColumnAnnotation extends Annotation {
	const PRIMARY_KEY = 'PrimaryKey';
	const AUTO_INCREMENT = 'AutoIncrement';
	
	public function usage() {
		return '!Column type [, PrimaryKey] [, AutoIncrement]';
	}
	
	public function isFor() {
		return Annotation::FOR_PROPERTY;
	}

	protected function validate($class) {
		$this->acceptsNoKeyedValues();
		$this->minimumParameterCount(1);
		$this->maximumParameterCount(3);
		$this->acceptedKeylessValues(array_merge(RecessType::all(), array('PrimaryKey', 'AutoIncrement')));
	}
	
	protected function expand($class, $reflection, $descriptor) {
		$propertyName = $reflection->getName();
		if(isset($descriptor->properties[$propertyName])) {
			$property = &$descriptor->properties[$propertyName];
			$property->type = $this->valueNotIn(array(self::PRIMARY_KEY, self::AUTO_INCREMENT));
			$property->isPrimaryKey = $this->isAValue(self::PRIMARY_KEY);
			$property->isAutoIncrement = $this->isAValue(self::AUTO_INCREMENT);
			
			if($property->isPrimaryKey) {
				$descriptor->primaryKey = $propertyName;
			}
		}
	}
}
?>