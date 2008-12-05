<?php
/**
 * !Database Default
 * !Table books
 */
class Book extends Model {
	/** !Column PrimaryKey, Integer, AutoIncrement */
	public $id;

	/** !Column String */
	public $title;

	/** !Column Integer */
	public $authorId;

}
?>