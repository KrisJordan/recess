<?php
Library::import('recess.database.orm.Model');

/**
 * !HasMany classes, Class: RecessReflectorClass, ForeignKey: packageId
 * !HasMany children, Class: RecessReflectorPackage, ForeignKey: parentId
 * !BelongsTo parent, Class: RecessReflectorPackage, ForeignKey: parentId
 * !Table packages
 */
class RecessReflectorPackage extends Model {
	
	/** !Column PrimaryKey, integer, AutoIncrement */
	public $id;
	
	/** !Column text */
	public $name;
	
	/** !Column integer */
	public $parentId;
	
	/** !Column integer */
	public $modified;
	
	function childrenAlphabetically() {
		return $this->children()->orderBy('name ASC');
	}
	
	function __construct($name = '') {
		if($name != '') {
			$this->name = $name;
		}
	}
	
	function insert() {
		echo 'Inserting package: ' . $this->name . '<br />';
		parent::insert();
		
		$dotPosition = strrpos($this->name, Library::dotSeparator);
		
		if($dotPosition !== false) { 
			$parentName = substr($this->name, 0, $dotPosition);
			
			$parent = new RecessReflectorPackage($parentName);
			
			if(!$parent->exists()) {
				$parent->insert();
			}
			$this->setParent($parent);
		}
	}
	
}

?>