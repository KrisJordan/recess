<?php
/**
 * This exception is thrown when the PdoDataSource is instantiated
 * with a PDO Driver DSN that does not have an associated Recess!
 * IPdoDataSourceProvider.
 * 
 * @author Kris Jordan
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link http://www.recessframework.org/
 */
class ProviderDoesNotExistException extends RecessException {}
?>