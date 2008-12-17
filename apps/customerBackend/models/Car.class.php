<?php
/**
 * !Database Default
 * !Table cars
 */
class Car extends Model {
	/** !Column PrimaryKey, Integer, AutoIncrement */
	public $id;

	/** !Column Boolean */
	public $isDriveable;

	/** !Column String */
	public $make;

	/** !Column String */
	public $model;

}
?>