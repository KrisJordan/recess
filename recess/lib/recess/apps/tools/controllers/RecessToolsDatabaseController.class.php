<?php
Library::import('recess.framework.controllers.Controller');
Library::import('recess.database.Databases');
Library::import('recess.database.pdo.PdoDataSource');

/**
 * !View Native, Prefix: database/
 * !RoutesPrefix database/
 */
class RecessToolsDatabaseController extends Controller {
	
	/** !Route GET */
	public function home() {
		$this->default = Databases::getDefaultSource();
		$this->sources = Databases::getSources();
	}
	
	/** !Route GET, source/$sourceName */
	public function showSource($sourceName) {
		$source = Databases::getSource($sourceName);
		
		if($source == null) {
			return $this->redirect($this->urlTo('home'));
		} else {
			$this->source = $source;
		}
		
		if($sourceName != 'Default') {
			$this->dsn = RecessConf::$namedDatabases[$sourceName];
		} else {
			$this->dsn = RecessConf::$defaultDatabase[0];
		}
		
		$this->name = $sourceName;
		$this->tables = $this->source->getTables();
		$this->driver = $this->source->getAttribute(PDO::ATTR_DRIVER_NAME);
	}
	
	/** !Route GET, source/$sourceName/table/$tableName */
	public function showTable($sourceName, $tableName) {
		$source = Databases::getSource($sourceName);
		if($source == null) {
			return $this->redirect($this->urlTo('home'));
		} else {
			$this->source = $source;
		}
		
		$this->sourceName = $sourceName;
		$this->table = $tableName;
		$this->columns = $this->source->getTableDescriptor($tableName)->getColumns();
	}
	
	/** !Route GET, source/$sourceName/table/$tableName/drop */
	public function dropTable($sourceName, $tableName) {
		$this->sourceName = $sourceName;
		$this->tableName = $tableName;
	}
	
	/** !Route POST, source/$sourceName/table/$tableName/drop */
	public function dropTablePost($sourceName, $tableName) {
		$source = Databases::getSource($sourceName);
		$source->dropTable($tableName);
		return $this->forwardOk($this->urlTo('showSource', $sourceName));
	}
	
	/** !Route GET, source/$sourceName/table/$tableName/empty */
	public function emptyTable($sourceName, $tableName) {
		$this->sourceName = $sourceName;
		$this->tableName = $tableName;
	}
	
	/** !Route POST, source/$sourceName/table/$tableName/empty */
	public function emptyTablePost($sourceName, $tableName) {
		$source = Databases::getSource($sourceName);
		$source->emptyTable($tableName);
		return $this->forwardOk($this->urlTo('showTable', $sourceName, $tableName));
	}
	
	private function getDsn($sourceName) {
		if($sourceName != 'Default') {
			$this->dsn = RecessConf::$defaultDatabase[0];
		} else {
			$this->dsn = RecessConf::$namedDatabases[$sourceName];
		}
	}
	
	/** !Route GET, new-source */
	public function newSource() {
		
	}
	
}

?>