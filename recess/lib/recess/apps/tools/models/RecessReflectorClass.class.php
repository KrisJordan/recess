<?php
Library::import('recess.database.orm.Model');
Library::import('recess.apps.ide.models.RecessReflectorClassProperties');
Library::import('recess.apps.ide.models.RecessReflectorClassMethods');

/**
 * !BelongsTo package, Class: RecessReflectorPackage, ForeignKey: packageId
 * !BelongsTo parent, Class: RecessReflectorClass, ForeignKey: parentId
 * !HasMany children, Class: RecessReflectorClass, ForeignKey: parentId
 * !Table classes
 */
class RecessReflectorClass extends Model {
	
	/** !Column PrimaryKey, integer, AutoIncrement */
	public $id;
	
	/** !Column text */
	public $name;
	
	/** 
	 * !Column integer
	 */
	public $parentId;
	
	/** 
	 * !Column integer
	 */
	public $packageId;
	
	/** !Column text */
	public $docComment;
	
	/** !Column text */
	public $file;
	
	/** !Column integer */
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