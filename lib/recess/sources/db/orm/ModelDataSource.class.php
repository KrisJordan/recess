<?php

Library::import('recess.sources.db.pdo.PdoDataSource');
Library::import('recess.sources.db.orm.MutableModelSet');

class ModelDataSource extends PdoDataSource {
	
	function selectModelSet($table = '') {
		if($table != '') {
			$mutableModelSet = new MutableModelSet($this);
			return $mutableModelSet->from($table);
		} else {
			return new MutableModelSet($this);
		}
	}
	
}

?>