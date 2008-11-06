<?php
Library::import('recess.sources.db.pdo.exceptions.DataSourceCouldNotConnectException');
Library::import('recess.sources.db.pdo.exceptions.ProviderDoesNotExistException');
Library::import('recess.sources.db.pdo.PdoDataSet');

/**
 * A PDO wrapper in the Recess! Framework that provides a single interface for commonly 
 * needed operations (i.e.: list tables, list columns in a table, etc).
 * 
 * @author Kris Jordan
 */
class PdoDataSource extends PDO {
	const PROVIDER_CLASS_LOCATION = 'recess.sources.db.pdo.';
	const PROVIDER_CLASS_SUFFIX = 'DataSourceProvider';
	
	protected $provider = null;
	
	function __construct($dsn, $username = '', $password = '', $driver_options = array()) {
		try {
			parent::__construct($dsn, $username, $password, $driver_options);
			parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $exception) {
			throw new DataSourceCouldNotConnectException($exception->getMessage(), get_defined_vars());
		}
		
		$this->provider = $this->instantiateProvider();
	}
	
	/**
	 * Locate the pdo driver specific data source provider, instantiate, and return.
	 * Throws ProviderDoesNotExistException for a pdo driver without a Recess! provider.
	 *
	 * @return IPdoDataSourceProvider
	 */
	protected function instantiateProvider() {
		$driver = ucfirst(parent::getAttribute(PDO::ATTR_DRIVER_NAME));
		$providerClass = $driver . self::PROVIDER_CLASS_SUFFIX;
		$providerFullyQualified = self::PROVIDER_CLASS_LOCATION . $providerClass;
		// Library::import($providerFullyQualified);
		if(Library::classExists($providerFullyQualified)) {
			$provider = new $providerClass;
			$provider->init($this);
			return $provider;
		} else {
			throw new ProviderDoesNotExistException($provider, get_defined_vars());	
		}
	}
	
	/**
	 * Begin a select operation by returning a new, unrealized PdoDataSet
	 *
	 * @param string $table Optional parameter that sets the from clause of the select to a table.
	 * @return PdoDataSet
	 */
	function select($table = '') {
		if($table != '') {
			$PdoDataSet = new PdoDataSet($this);
			return $PdoDataSet->from($table);
		} else {
			return new PdoDataSet($this);
		}
	}
	
	/**
	 * Takes the SQL and arguments (array of Criterion) and returns an array
	 * of objects of type $className.
	 * 
	 * @todo Determine edge conditions and throws.
	 * @param string $query
	 * @param array(Criterion) $arguments 
	 * @param string $className the type to fill from query results.
	 * @return array($className)
	 */
	function queryForClass($query, $arguments, $className) {
		if($query == '') return array();
		$statement = $this->prepareStatement($query, $arguments);
		$statement->setFetchMode(PDO::FETCH_CLASS, $className, array());
		$statement->execute();	
		return $statement->fetchAll();
	}
	
	function executeStatement($statement, $arguments) {
		$statement = $this->prepareStatement($statement, $arguments);
		return $statement->execute();
	}
	
	function prepareStatement($statement, $arguments) {
		try {
			$statement = $this->prepare($statement);
		} catch(PDOException $e) {
			throw new RecessException($e->getMessage() . ' SQL: ' . $statement,get_defined_vars());
		}
		
		foreach($arguments as $argument) {
			$statement->bindValue($argument->getQueryParameter(), $argument->value);
		}
		
		return $statement;
	}
	
	/**
	 * List the tables in a data source alphabetically.
	 * @return array(string) The tables in the data source
	 */
	function getTables() { 
		return $this->provider->getTables();
	}
	
	/**
	 * List the column names of a table alphabetically.
	 * @param string $table Table whose columns to list.
	 * @return array(string) Column names sorted alphabetically.
	 */
	function getColumns($table) {
		return $this->provider->getColumns($table);
	}
}

?>