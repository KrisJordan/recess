<?php
Layout::extend('layouts/apps');
$title = $app->name;
?>
<h1><?php echo $app->name; ?></h1>
<p>Class: 
	<a href="<?php 
	echo Url::action('RecessToolsCodeController::classInfo', Library::getFullyQualifiedClassName(get_class($app))) ?>"><?php echo get_class($app); ?></a></p>
<div class="span-6">
<h2 class="bottom">Models (<a href="<?php echo $controller->urlTo('createModel',get_class($app)); ?>">new</a>)</h2>
<p>Location: <?php echo Html::anchor(Url::action('RecessToolsCodeController::packageInfo',substr($app->modelsPrefix,0,-1)), $app->modelsPrefix ) ?> </p>
<?php
function printClassesInNamespace($namespace) {
	$classes = Library::findClassesIn($namespace);
	if(!empty($classes)) {
		echo '<ul>';
		foreach($classes as $class) {
			echo '<li>' . Html::anchor(Url::action('RecessToolsCodeController::classInfo', $namespace . $class), $class) . '</li>';
		}
		echo '</ul>';
	}
}
printClassesInNamespace($app->modelsPrefix);
?>
</div>
<div class="span-6">
<h2 class="bottom">Controllers (<a href="<?php echo $controller->urlTo('createController', get_class($app)); ?>">new</a>)</h2>
<p>Location: <?php echo Html::anchor(Url::action('RecessToolsCodeController::packageInfo',substr($app->controllersPrefix,0,-1)), $app->controllersPrefix ) ?></p>
<?php
printClassesInNamespace($app->controllersPrefix);
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
// include_once($viewsDir . 'common/printRoutes.php');
Part::draw('routes/table', $routes, '');
?>

<hr />
<p>Trying to <a href="<?php echo $controller->urlTo('uninstall',get_class($app)); ?>">uninstall <?php echo $app->name; ?></a>?</p>