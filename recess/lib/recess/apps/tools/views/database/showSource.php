<?php
$title = 'Data Sources - ' . $name;
$selectedNav = 'database';
include_once($viewsDir . 'common/header.php');
?>

<h1><strong><?php echo $name; ?></strong> Data Source (<?php echo $driver; ?>)</h1>
<p>DSN: <strong><?php echo $dsn; ?></strong></p>

<h2>Tables:</h2>

<?php foreach($tables as $table): ?>
	<h3><a href="<?php echo $controller->urlTo('showTable',$name,$table); ?>"><?php echo $table; ?></a></h3>
<?php endforeach ?>

<hr />



<?php include_once($viewsDir . 'common/footer.php'); ?>