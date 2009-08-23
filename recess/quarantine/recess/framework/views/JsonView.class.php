<?php
Library::import('recess.framework.AbstractView');

class JsonView extends AbstractView {
	
	public function canRespondWith(Response $response) {
		return 'json' === $response->request->accepts->format();
	}
	
	protected function render(Response $response) {
		$response = clone $response;
		foreach($response->data as $key => $value) {
			if($value instanceof ModelSet) {
				$response->data[$key] = $value->toArray();
			}
			if($value instanceof Form) {
				unset($response->data[$key]);
			}
			if(substr($key,0,1) == '_') {
				unset($response->data[$key]);
			}
		}
		if(isset($response->data['application'])) unset ($response->data['application']);
		if(isset($response->data['controller'])) unset ($response->data['controller']);
		echo json_encode($response->data);
	}
}
?>