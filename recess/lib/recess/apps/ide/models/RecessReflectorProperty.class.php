<?php
Library::import('recess.sources.db.orm.Model');

/**
 * !Table properties
 */
class RecessReflectorProperty extends Model {
	
	/** !PrimaryKey integer, AutoIncrement: true */
	public $id;
	
	/** !Type text */
	public $name;
	
}

?>