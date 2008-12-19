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

	/** !Column DateTime */
	public $created;

	/** !Column Timestamp */
	public $updated;

}
?>