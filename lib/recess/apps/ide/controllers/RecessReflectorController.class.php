<?php
Library::import('recess.framework.controllers.Controller');
Library::import('app.controllers.RouteAnnotation', true);
Library::import('recess.http.responses.NotFoundResponse');
Library::import('recess.framework.ResponseData');
Library::import('recess.http.responses.OkResponse');

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
		
		$data = new stdclass;
		$data->reflection = $reflection;
		$data->relationships = Model::getRelationships($model);
		$data->columns = Model::getColumns($model);
		$data->table = Model::tableFor($model);
		
		return $this->ok();
		
//		$response = new OkResponse($this->request, $data);
//		$response->meta->app = new RecessIdeApplication();
//		$response->meta->viewName = 'reflector/model';
//		return $response;
	}
	
}

?>