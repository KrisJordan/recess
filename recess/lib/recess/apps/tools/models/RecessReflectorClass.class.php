<?php
Library::import('recess.sources.db.orm.Model');
Library::import('recess.apps.ide.models.RecessReflectorClassProperties');
Library::import('recess.apps.ide.models.RecessReflectorClassMethods');

/**
 * !HasMany properties, Class: RecessReflectorProperty, Through: RecessReflectorClassProperties, ForeignKey: propertyId, OnDelete: Delete
 * !HasMany methods, Class: RecessReflectorMethod, Through: RecessReflectorClassMethods, ForeignKey: methodId, OnDelete: Delete
 * !BelongsTo package, Class: RecessReflectorPackage, ForeignKey: packageId
 * !BelongsTo parent, Class: RecessReflectorClass, ForeignKey: parentId
 * !HasMany children, Class: RecessReflectorClass, ForeignKey: parentId
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
	
	public function fromClass($class, $dir = '') {
		
		$classInfo = new RecessReflectionClass($class);
		
		$this->docComment = $classInfo->getDocComment();
		
		$this->file = $classInfo->getFileName();
		
		$this->lastModified = filemtime($this->file);
		
		$package = Library::getPackage($class);

		if($dir != '') {
			if(strpos($dir, '/' . str_replace('.','/',$package)) !== 0) {
				throw new RecessException('The class: ' . $class . ' has been imported incorectly with ' . $package . '.' . $class . '. The real location is: ' . $dir, get_defined_vars());
			}
		}
			
		if($package != '') {
			$packageReflector = new RecessReflectorPackage();
			$packageReflector->name = $package;
			$packageInstance = $packageReflector->find()->first();
			
			if($packageReflector->exists()) {
				$this->setPackage($packageReflector->find()->first());
			} else {
				$packageReflector->insert();
				$this->setPackage($packageReflector);
			}
		}
		
		$this->save();
		
		$parent = $classInfo->getParentClass();
		
		if($parent != null) {
			$parentReflectorClass = new RecessReflectorClass();
			$parentReflectorClass->name = $parent->name;
			$exists = $parentReflectorClass->find()->first();
			if(!$exists) {
				$parentReflectorClass->fromClass($parent->name);
				$this->setParent($parentReflectorClass);
			} else {
				$this->setParent($exists);
			}
		}
		
		
	}
	
}
?>