<?php
/**
 * !Source Default
 * !Table classes
 */
class fdas extends Model {
	/** !Type integer */
	public $id;

	/** !Type text */
	public $name;

	/** !Type integer */
	public $parentId;

	/** !Type integer */
	public $packageId;

	/** !Type text */
	public $docComment;

	/**
	 * !Type text
	 * !Required
	 */
	public $file;

	/** !Column integer, PrimaryKey */
	public $lastModified;

}
?>