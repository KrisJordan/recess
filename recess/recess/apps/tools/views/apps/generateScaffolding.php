<?php
Layout::extend('layouts/apps');
$title = 'Scaffolding Generation';
?>

<h1>Generating Scaffolding for <?php echo $modelName; ?>...</h1>

<pre>
<?php
foreach($messages as $message) {
	echo $message, '<br />';
}
?>
</pre>

<h2><a href="<?php echo $controller->urlTo('app', $appName); ?>">Back to Application</a></h2>