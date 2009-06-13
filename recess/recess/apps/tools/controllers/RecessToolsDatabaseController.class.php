<?php
Library::import('recess.framework.controllers.Controller');
Library::import('recess.database.Databases');
Library::import('recess.database.pdo.PdoDataSource');

/**
 * !RespondsWith Layouts, Json
 * !Prefix database/
 */
class RecessToolsDatabaseController extends Controller {
	
	public function init() {
		if(RecessConf::$mode == RecessConf::PRODUCTION) {
			throw new RecessResponseException('Tools are available only during development.', ResponseCodes::HTTP_NOT_FOUND, array());
		}
	}
	
	/** !Route GET */
	public function home() {
		$this->default = Databases::getDefaultSource();
		$this->sources = Databases::getSources();
		
		$this->sourceInfo = array();
		foreach($this->sources as $name => $source) {
			if($name != 'Default') {
				$this->sourceInfo[$name]['dsn'] = RecessConf::$namedDatabases[$name];
			} else {
				$this->sourceInfo[$name]['dsn'] = RecessConf::$defaultDatabase[0];
			}
			$this->sourceInfo[$name]['tables'] = $source->getTables();
			$this->sourceInfo[$name]['driver'] = $source->getAttribute(PDO::ATTR_DRIVER_NAME);
		}
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
		return $this->forwardOk($this->urlTo('home'));
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