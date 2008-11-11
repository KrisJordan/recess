<?php
Library::import('recess.sources.db.orm.Model');

/**
 * !HasMany recessReflectorClasses, Class: RecessReflectorClass
 * !HasMany children, Class: RecessReflectorPackage, ForeignKey: parentId
 * !BelongsTo parent, Class: RecessReflectorPackage
 * !Table packages
 */
class RecessReflectorPackage extends Model {
	
	/** !PrimaryKey integer, AutoIncrement: true */
	public $id;
	
	/** !Type text */
	public $name;
	
	/**
	 * !ForeignKey Table: packages
	 * !Type integer
	 */
	public $parentId;
	
	/**
	 * !Type integer
	 */
	public $modified;
	
	function childrenAlphabetically() {
		return $this->children()->orderBy('name ASC');
	}
	
}

?>