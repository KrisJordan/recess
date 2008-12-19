<?php
$title = 'Browsing Code by Package';
$selectedNav = 'code';
include_once($viewsDir . 'common/header.php');
?>

<h1>Browse Code by Package</h1>

<ul>
<?php foreach($packages as $package): ?>
	<li><?php echo $package->name; ?></li>
<?php endforeach; ?>
</ul>

<?php include_once($viewsDir . 'common/footer.php'); ?>