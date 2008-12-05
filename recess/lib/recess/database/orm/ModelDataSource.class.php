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
	
	function createTable(ModelDescriptor $descriptor) {
		// Here we need to go between model descriptor and
		// Recess Table Definition
		Library::import('recess.database.pdo.RecessTableDefinition');
	}
	
}
?>