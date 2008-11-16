<?php
Library::import('recess.framework.controllers.Controller');
Library::import('recess.http.responses.NotFoundResponse');
Library::import('recess.http.responses.OkResponse');

/**
 * !View Smarty, Prefix: reflector/
 */
class RecessReflectorController extends Controller {
	
	/** !Route GET, reflector/model/$fullyQualifiedModel */
	public function model($fullyQualifiedModel) {
		if(!Library::classExists($fullyQualifiedModel)) {
			return new NotFoundResponse($this->request);
		}

		$model = Library::getClassName($fullyQualifiedModel);
		$reflection = new RecessReflectionClass($model);
		
		$this->reflection = $reflection;
		$this->relationships = Model::getRelationships($model);
		$this->columns = Model::getColumns($model);
		$this->table = Model::tableFor($model);

		return $this->ok();
	}
	
	/** !Route GET, reflector/model/$fullyQualifiedModel/create */
	function createTable ($fullyQualifiedModel) {
		if(!Library::classExists($fullyQualifiedModel)) {
			return new NotFoundResponse($this->request);
		}	

		$this->sql = '';

		$class = Library::getClassName($fullyQualifiedModel);
		
		$props = Model::getProperties($class);
		
		$table = Model::tableFor($class);
		
		$this->sql = 'CREATE TABLE ' . $table . ' ( ';
		
		$first = true;
		foreach($props as $prop) {
			if($first) { $first = false; }
			else { $this->sql .= ', '; }
			$this->sql .= $prop->name . ' ' . $prop->type;
		}
		
		$this->sql .= ' );';
		
	}
	
}

?>