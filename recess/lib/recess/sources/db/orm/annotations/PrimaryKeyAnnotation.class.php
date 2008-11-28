<?php
Library::import('recess.sources.db.orm.annotations.ModelPropertyAnnotation');

class PrimaryKeyAnnotation extends ModelPropertyAnnotation {
	public $settings;
	public $type;
	public $autoincrement = false;
	public $callback = '';
	
	function init($array) {
		$this->settings = $array;
		$this->type = $array[0];
		if (isset($array['AutoIncrement'])) {
			$this->autoincrement = $array['AutoIncrement'];
		}
		if(isset($array['Callback'])) {
			$this->callback = $array['Callback'];
		}
	}
	
	function massage(ModelProperty $property) {
		$property->isPrimaryKey = true;
		$property->type = $this->type;
		$property->autoincrement = $this->autoincrement;
		$property->pkCallback = $this->callback;
	}
}

?>