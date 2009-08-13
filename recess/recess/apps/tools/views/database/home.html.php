<?php
Layout::extend('layouts/database');
$title = 'Home';
?>
<h1>Data Sources</h1>
<hr />
<?php foreach($sources as $name => $source): ?>
	<h2 class="bottom"><?php echo $name; ?> (<?php echo $sourceInfo[$name]['driver']; ?>)</h2>
	<div style="margin: 0 0 0 2em">
	<p>DSN: <?php echo $sourceInfo[$name]['dsn']; ?></p>
	<h3 class="bottom">Tables:</h3>
	<ul style="font-size: 1.8em">
	<?php foreach($sourceInfo[$name]['tables'] as $table): ?>
		<li><a href="<?php echo $controller->urlTo('showTable',$name,$table); ?>"><?php echo $table; ?></a></li>
	<?php endforeach; ?>
	</ul>
	</div>
	<hr />
<?php endforeach; ?>
<h3><a href="<?php echo $controller->urlTo('newSource'); ?>">Add another Data Source</a></h3>