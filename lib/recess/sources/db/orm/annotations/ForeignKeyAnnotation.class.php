<?php
Library::import('recess.sources.db.orm.annotations.ModelPropertyAnnotation');

class ForeignKeyAnnotation extends ModelPropertyAnnotation {
	function init($array) {	}
	
	function massage(ModelProperty $property) {
		$property->isForeignKey = true;
	}
}

?>