<?php
/**
 * !Source Default
 * !Table posts
 */
class Post extends Model {
	/** !Type integer */
	public $id;

	/** !Type string */
	public $title;

	/** !Type text */
	public $body;

	/** !Type boolean */
	public $public;

	/** !Type timestamp */
	public $lastEdited;

}
?>