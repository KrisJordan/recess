<?php
$title = 'Applications - ' . $app->name;
$selectedNav = 'apps';
include_once($viewsDir . 'common/header.php');

Library::import('recess.apps.tools.controllers.RecessToolsCodeController');
$codeController = new RecessToolsCodeController($response->request->meta->app);
?>
<h1><?php echo $app->name; ?></h1>
<p>Class: 
	<a href="<?php 
	echo $codeController
			->urlToMethod(
					'classInfo',
					Library::getFullyQualifiedClassName(
						get_class($app)
					)
				); ?>"><?php echo get_class($app); ?></a></p>
<div class="span-6">
<h2 class="bottom">Models (<a href="<?php echo $controller->urlToMethod('createModel',get_class($app)); ?>">new</a>)</h2>
<p>Location: <a href="<?php echo $codeController->urlToMethod('packageInfo', substr($app->modelsPrefix,0,-1)); ?>"><?php echo $app->modelsPrefix; ?></a></p>
<?php
function printClassesInNamespace($namespace, $codeController) {
	$classes = Library::findClassesIn($namespace);
	if(!empty($classes)) {
		echo '<ul>';
		foreach($classes as $class) {
			echo '<li><a href="' . $codeController->urlToMethod('classInfo',$namespace . $class) . '">' . $class . '</a></li>';
		}
		echo '</ul>';
	}
}
printClassesInNamespace($app->modelsPrefix, $codeController);
?>
</div>
<div class="span-6">
<h2 class="bottom">Controllers (<a href="<?php echo $controller->urlToMethod('createController'); ?>">new</a>)</h2>
<p>Location: <a href="<?php echo $codeController->urlToMethod('packageInfo', substr($app->controllersPrefix,0,-1)); ?>"><?php echo $app->controllersPrefix; ?></a></p>
<?php
printClassesInNamespace($app->controllersPrefix, $codeController);
?>
</div>

<div class="span-5 last">
<h2 class="bottom">Views</h2>
<p>Location: <?php echo $app->viewsDir; ?></p>
</div>
<hr />

<h2 class="bottom">Routes</h2>
<p class="bottom">Route Prefix: <?php echo $app->routingPrefix; ?></p>
<?php
$routes = new RtNode();
$app->addRoutesToRouter($routes);
include_once($viewsDir . 'common/printRoutes.php');
printRoutes($routes, $codeController);
?>

<hr />
<p>Trying to <a href="<?php echo $controller->urlToMethod('uninstall',get_class($app)); ?>">uninstall <?php echo $app->name; ?></a>?</p>

<?php include_once($viewsDir . 'common/footer.php'); ?>