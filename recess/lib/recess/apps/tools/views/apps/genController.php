<?php
$title = 'Scaffolding Generation';
$selectedNav = 'apps';
include_once($viewsDir . 'common/header.php');
?>

<h1>Select the Model to Generate Scaffolding for:</h1>
<ul style="font-size: 2em;">
<?php
function printClassesInNamespace($namespace, $controller, $app) {
	$classes = Library::findClassesIn($namespace);
	if(!empty($classes)) {
		echo '<ul>';
		foreach($classes as $class) {
			echo '<li><a href="' . $controller->urlTo('generateScaffolding',get_class($app), $class) . '">' . $class . '</a></li>';
		}
		echo '</ul>';
	}
}
printClassesInNamespace($app->modelsPrefix, $controller, $app);
?>
</ul>

<hr />
<h2><a href="<?php echo $controller->urlTo('app', get_class($app)); ?>">Back to Application</a></h2>

<?php include_once($viewsDir . 'common/footer.php'); ?>