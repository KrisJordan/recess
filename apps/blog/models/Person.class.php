<?php
/**
 * !Database Default
 * !Table persons
 */
class Person extends Model {
	/** !Column PrimaryKey, Integer, AutoIncrement */
	public $id;

	/** !Column String */
	public $firstName;

	/** !Column String */
	public $lastName;

	/** !Column Integer */
	public $age;

	/** !Column Date */
	public $birthday;

}
?>