<?php
$title = 'Data Sources';
$selectedNav = 'database';
include_once($viewsDir . 'common/header.php');
?>
<h1>Data Sources</h1>

<?php
foreach($sources as $name => $source) {
	echo '<h2><a href="', $controller->urlTo('showSource', $name), '">', $name, '</a></h2>';
}
?>
<hr />
<h3><a href="<?php echo $controller->urlTo('newSource'); ?>">Add another Data Source</a></h3>
<?php include_once($viewsDir . 'common/footer.php'); ?>