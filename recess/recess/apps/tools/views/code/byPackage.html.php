<?php
Layout::extend('layouts/code');
$title = 'By Package';
?>

<h1>Browse Code by Package</h1>

<ul>
<?php foreach($packages as $package): ?>
	<li><?php echo $package->name; ?></li>
<?php endforeach; ?>
</ul>