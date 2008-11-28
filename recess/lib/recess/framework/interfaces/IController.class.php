<?php

Library::import('recess.http.Request');

interface IController {
	function serve(Request $request);
}

?>