<?php

/**
 * This exception is thrown when the PdoDataSource is instantiated
 * with a PDO Driver DSN that does not have an associated Recess!
 * IPdoDataSourceProvider.
 * 
 * @author Kris Jordan
 */
class ProviderDoesNotExistException extends RecessException {}

?>