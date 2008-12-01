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
<h2 class="bottom">Models</h2>
<a href="<?php echo $controller->urlToMethod('createModel'); ?>">Create New Model</a>
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
<h2 class="bottom">Controllers</h2>
<a href="<?php echo $controller->urlToMethod('createController'); ?>">Create New Controller</a>
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
$routes = new RoutingNode();
$app->addRoutesToRouter($routes);
$routes = $routes->getStaticPaths();

echo '<table>';
echo '<thead><td>HTTP</td><td>Route</td><td>Controller</td><td>Method</td></thead>';
echo '<tbody>';
$fullPath = $_ENV['url.base'];
if(strrpos($fullPath, '/') == strlen($fullPath) - 1) $fullPath = substr($fullPath,0,-1);
printRoutes($codeController, array_shift($routes), $fullPath);
echo '</tbody>';
echo '</table>';

function printRoutes($codeController, $routingNode, $fullPath) {
	if($routingNode == null) return;
	$staticPaths = $routingNode->getStaticPaths();
	$parametricPaths = $routingNode->getParametricPaths();
	$methods = $routingNode->getMethods();
	if(!empty($methods)) {
		foreach($methods as $method => $route) {
			echo '<tr><td>' . $method . '</td>';
			echo '<td>' . $fullPath . '</td>';
			echo '<td><a href="' . $codeController->urlToMethod('classInfo',$route->class) . '">' . Library::getClassName($route->class) . '</a></td>';
			echo '<td><a href="' . $codeController->urlToMethod('classInfo',$route->class) . '#method_' . $route->function . '">'. $route->function . '</a></td>';
			echo '</tr>';
		}		
	} 

	if(!empty($staticPaths) || !empty($parametricPaths)) {
		ksort($staticPaths);
		ksort($parametricPaths);
		foreach($staticPaths as $path => $node) {
			printRoutes($codeController, $node, $fullPath . '/' . $path);
		}
		foreach($parametricPaths as $path => $node) {
			printRoutes($codeController, $node, $fullPath . '/$' . $path);
		}
	}
}
?>

<hr />
<p>Trying to <a href="<?php echo $controller->urlToMethod('uninstall',get_class($app)); ?>">uninstall <?php echo $app->name; ?></a>?</p>

<?php include_once($viewsDir . 'common/footer.php'); ?>