<?php
Library::import('recess.sources.db.orm.Model');

/**
 * !HasMany properties, Class: RecessReflectorProperty, Through: ClassProperties, ForeignKey: propertyId
 * !HasMany methods, Class: RecessReflectorMethod, Through: ClassMethods, ForeignKey: methodId
 * !BelongsTo package, Class: RecessReflectorPackage, ForeignKey: packageId
 * !BelongsTo parent, Class: RecessReflectorClass, ForeignKey: parentId
 * !Table classes
 * !Source reflector
 */
class RecessReflectorClass extends Model {
	
	/** !PrimaryKey integer, AutoIncrement: true */
	public $id;
	
	/** !Type text */
	public $name;
	
	/** 
	 * !ForeignKey classes
	 * !Type integer
	 */
	public $parentId;
	
	/** 
	 * !ForeignKey packages
	 * !Type integer
	 */
	public $packageId;
	
	/** !Type text */
	public $docComment;
	
	/** !Type text */
	public $file;
	
	/** !Type integer */
	public $lastModified;
	
}
?>