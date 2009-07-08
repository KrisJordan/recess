<?php
Layout::extend('layouts/routes');
$title = 'Home';
?>
<h2 class="bottom">Routes</h2>
<?php
$routes = RecessConf::getRoutes();
Part::draw('routes/table', $routes, '/recess');
?>