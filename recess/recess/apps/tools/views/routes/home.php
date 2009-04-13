<?php
Layout::extend('layouts/routes');
Layout::blockAssign('title', 'Home');
?>
<h2 class="bottom">Routes</h2>
<?php
//include_once($viewsDir . 'common/printRoutes.php');
$routes = RecessConf::getRoutes();

Library::import('recess.apps.tools.controllers.RecessToolsCodeController');
$codeController = new RecessToolsCodeController($response->request->meta->app);

// printRoutes($routes, $codeController, '/recess');
Part::render('routes/table', $routes, $codeController, '/recess');
?>