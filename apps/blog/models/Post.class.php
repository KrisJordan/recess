<?php
/**
 * !Database Default
 * !Table posts
 */
class Post extends Model {
	
	/** !Column PrimaryKey, Integer, AutoIncrement */
	public $id;

	/** !Column String */
	public $title;

	/** !Column Text */
	public $body;

	/** !Column Boolean */
	public $isPublic;

	/** !Column Timestamp */
	public $modifiedAt;

	/** !Column DateTime */
	public $createdOn;

}
?>