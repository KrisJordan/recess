<?php
Library::import('recess.database.pdo.PdoDataSource');
Library::import('recess.database.orm.ModelSet');

class ModelDataSource extends PdoDataSource {

	function selectModelSet($table = '') {
		if($table != '') {
			$ModelSet = new ModelSet($this);
			return $ModelSet->from($table);
		} else {
			return new ModelSet($this);
		}
	}
	
	function createTableSql(ModelDescriptor $descriptor) {
		// Here we need to go between model descriptor and
		// Recess Table Definition
		Library::import('recess.database.pdo.RecessTableDescriptor');
		Library::import('recess.database.pdo.RecessColumnDescriptor');
		$tableDescriptor = new RecessTableDescriptor();
		$tableDescriptor->name = $descriptor->getTable();
		foreach($descriptor->properties as $property) {
			$tableDescriptor->addColumn(
								$property->name,
								$property->type,
								true,
								$property->isPrimaryKey,
								array(),
								($property->isAutoIncrement ? array('autoincrement' => true) : array())
							);
		}
		return parent::createTableSql($tableDescriptor);
	}
	
}
?>