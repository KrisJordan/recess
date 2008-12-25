<?php
/**
 * This exception is thrown when the PdoDataSource is instantiated
 * with a PDO Driver DSN that does not have an associated Recess!
 * IPdoDataSourceProvider.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class ProviderDoesNotExistException extends RecessException {}
?>