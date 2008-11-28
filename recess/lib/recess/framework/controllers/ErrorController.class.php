<?php

Library::import('recess.framework.interfaces.IController');
Library::import('recess.http.responses.NotFoundResponse');

class ErrorController implements IController {
	public function serve(Request $request) {
		return new NotFoundResponse($request);
	}
}

?>