<div class="navigation">
	<ul>
<?php
// This is a really sketchy way of building up navigation.
// Please do not actually use this in a real app. Thx -Kris
Library::import('recess.lang.Inflector');
$app = $controller->application();
$controllers = $app->listControllers();
foreach($controllers as $controllerClass): 
	$navController = new $controllerClass($app);
?>
		<li><a href="<?php echo $navController->urlTo('index'); ?>"><?php echo Inflector::toEnglish(str_replace('Controller','',$controllerClass)); ?></a></li>
<?php endforeach; ?>
	</ul>
</div>