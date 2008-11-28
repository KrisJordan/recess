<?php

Library::import('recess.sources.db.pdo.PdoDataSource');
Library::import('recess.sources.db.orm.ModelSet');

class ModelDataSource extends PdoDataSource {
	
	function selectModelSet($table = '') {
		if($table != '') {
			$ModelSet = new ModelSet($this);
			return $ModelSet->from($table);
		} else {
			return new ModelSet($this);
		}
	}
	
}

?>