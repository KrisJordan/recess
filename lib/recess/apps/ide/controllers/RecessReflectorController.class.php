<?php
Library::import('recess.framework.controllers.Controller');
Library::import('recess.http.responses.NotFoundResponse');
Library::import('recess.http.responses.OkResponse');

/**
 * !View Smarty, Prefix: reflector/
 */
class RecessReflectorController extends Controller {
	/** !Route GET, reflector/ */
	public function home() {
		echo 'ROUTED home';
	}
	
	/** !Route GET, reflector/package/$package */
	public function package($package) {
		echo 'package/' . $package; exit;
	}
	
	/** !Route GET, reflector/model/$fullyQualifiedModel */
	public function model($fullyQualifiedModel) {
		if(!Library::classExists($fullyQualifiedModel)) {
			return new NotFoundResponse($request);
		}
		
		$model = Library::getClassName($fullyQualifiedModel);
		$reflection = new RecessReflectionClass($model);
		
		$this->reflection = $reflection;
		$this->relationships = Model::getRelationships($model);
		$this->columns = Model::getColumns($model);
		$this->table = Model::tableFor($model);
		
		return $this->ok();
	}
	
}

?>