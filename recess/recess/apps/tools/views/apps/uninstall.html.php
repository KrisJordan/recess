<?php
Layout::extend('layouts/apps');
$title = 'Uninstall';
?>

<h1>To <span class="removed">Uninstall</span> <?php echo $app->name; ?>...</h1>
<ol>
	<li><span class="highlight">Open <?php echo $_ENV['dir.bootstrap']; ?>recess-conf.php</span></li>
	<li>Find the <span class="highlight">RecessConf::$applications</span> array.</li>
	<?php 
	$appClass = Library::getFullyQualifiedClassName(get_class($app));
	?>
	<li><span class="highlight">Remove the string '<?php echo $appClass; ?>'</span></li>
	<li>[Optional] Delete the directory <?php echo $_ENV['dir.apps'] . substr($appClass,0,strpos($appClass,'.')); ?></li>
</ol>
<h2>That's all folks. <a href="<?php echo $controller->urlTo('home'); ?>">Head back to apps.</a></h2>