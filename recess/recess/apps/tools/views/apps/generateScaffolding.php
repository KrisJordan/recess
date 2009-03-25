<?php
$title = 'Scaffolding Generation';
$selectedNav = 'apps';
include_once($viewsDir . 'common/header.php');
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

<?php include_once($viewsDir . 'common/footer.php'); ?>