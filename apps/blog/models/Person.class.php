<?php
/**
 * !Database Default
 * !Table persons
 */
class Person extends Model {
	/** !Column PrimaryKey, integer, AutoIncrement */
	public $id;

	/** !Column text */
	public $name;

	/** !Column integer */
	public $parentId;

	/** !Column integer */
	public $packageId;

	/** !Column text */
	public $docComment;

	/** !Column text */
	public $file;

	/** !Column integer */
	public $lastModified;

}
?>