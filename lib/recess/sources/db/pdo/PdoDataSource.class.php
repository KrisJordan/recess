<?php
Library::import('recess.sources.db.pdo.exceptions.DataSourceCouldNotConnectException');
Library::import('recess.sources.db.pdo.exceptions.ProviderDoesNotExistException');
Library::import('recess.sources.db.SelectedSet');

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
	 * Begin a select operation by returning a new, unrealized SelectedSet
	 *
	 * @param string $table Optional parameter that sets the from clause of the select to a table.
	 * @return SelectedSet
	 */
	function select($table = '') {
		if($table != '') {
			$selectedSet = new SelectedSet($this);
			return $selectedSet->from($table);
		} else {
			return new SelectedSet($this);
		}
	}
	
	/**
	 * Takes the SQL and parameters from SqlBuilder and returns an array
	 * of objects of type $className.
	 * 
	 * @todo Determine edge conditions and throws.
	 * @param SqlBuilder $sqlBuilder
	 * @param string $className the type to fill from query results.
	 * @return array($className)
	 */
	function queryFromSqlBuilder(SelectSqlBuilder $sqlBuilder, $className) {
		$query = $sqlBuilder->getSql();
		if($query == '') return array();
		
		try {
			$statement = $this->query($query, PDO::FETCH_CLASS, $className);
		} catch(PDOException $e) {
			throw new RecessException($e->getMessage() . ' SQL: ' . $query,get_defined_vars());		
		}
		
		$arguments = $sqlBuilder->getWhereArguments();
		foreach($arguments as $argument) {
			$statement->bindValue($argument->getQueryParameter(), $argument->value);
		}
		$statement->execute();
		
		return $statement->fetchAll();
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