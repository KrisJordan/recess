<?php
Library::import('recess.sources.db.orm.annotations');
class SourceAnnotation extends ModelAnnotation {
	public $source;
	
	function init($array) {
		$this->source = $array[0];	
	}
	
	function massage(ModelDescriptor &$descriptor) {
		// $descriptor->source = $this->source;
	}
}
?>