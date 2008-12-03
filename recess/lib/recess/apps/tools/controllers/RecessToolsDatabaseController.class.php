<?php
Library::import('recess.framework.controllers.Controller');
Library::import('recess.sources.db.DbSources');
Library::import('recess.sources.db.pdo.PdoDataSource');

/**
 * !View Native, Prefix: database/
 * !RoutesPrefix database/
 */
class RecessToolsDatabaseController extends Controller {
	
	/** !Route GET */
	public function home() {
		$this->default = DbSources::getDefaultSource();
		$this->sources = DbSources::getSources();
	}
	
	/** !Route GET, source/$sourceName */
	public function showSource($sourceName) {
		$source = DbSources::getSource($sourceName);
		
		if($source == null) {
			return $this->redirect($this->urlToMethod('home'));
		} else {
			$this->source = $source;
		}
		
		if($sourceName != 'Default') {
			$this->dsn = Config::$namedDataSources[$sourceName];
		} else {
			$this->dsn = Config::$defaultDataSource[0];
		}
		
		$this->name = $sourceName;
		$this->tables = $this->source->getTables();
		$this->driver = $this->source->getAttribute(PDO::ATTR_DRIVER_NAME);
	}
	
	/** !Route GET, source/$sourceName/table/$tableName */
	public function showTable($sourceName, $tableName) {
		$source = DbSources::getSource($sourceName);
		if($source == null) {
			return $this->redirect($this->urlToMethod('home'));
		} else {
			$this->source = $source;
		}
		
		$this->sourceName = $sourceName;
		$this->table = $tableName;
		$this->columns = $this->source->getTableDefinition($tableName)->getColumns();
		
	}
	
	/** !Route GET, source/$sourceName/table/$tableName/drop */
	public function dropTable($sourceName, $tableName) {
		$this->sourceName = $sourceName;
		$this->tableName = $tableName;
	}
	
	/** !Route POST, source/$sourceName/table/$tableName/drop */
	public function dropTablePost($sourceName, $tableName) {
		$source = DbSources::getSource($sourceName);
		$source->dropTable($tableName);
		return $this->forwardOk($this->urlToMethod('showSource', $sourceName));
	}
	
	/** !Route GET, source/$sourceName/table/$tableName/empty */
	public function emptyTable($sourceName, $tableName) {
		$this->sourceName = $sourceName;
		$this->tableName = $tableName;
	}
	
	/** !Route POST, source/$sourceName/table/$tableName/empty */
	public function emptyTablePost($sourceName, $tableName) {
		$source = DbSources::getSource($sourceName);
		$source->emptyTable($tableName);
		return $this->forwardOk($this->urlToMethod('showTable', $sourceName, $tableName));
	}
	
	private function getDsn($sourceName) {
		if($sourceName != 'Default') {
			$this->dsn = Config::$defaultDataSource[0];
		} else {
			$this->dsn = Config::$namedDataSources[$sourceName];
		}
	}
	
	/** !Route GET, new-source */
	public function newSource() {
		
	}
	
}

?>