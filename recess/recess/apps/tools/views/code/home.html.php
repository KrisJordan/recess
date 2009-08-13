<?php
Layout::extend('layouts/code');
$title = 'Code Browser';
?>
<h1>Code Browser</h1>
<?php if(count($packages) == 0):?>
	<h3><a href="<?php echo $controller->urlTo('index'); ?>">First time here? Index your code. (Note: this can take a few, please be patient!)</a></h3>
<?php endif; ?>
<h2>By Package</h2>
<?php Part::draw('code/table', $controller, $packages); ?>
<h2>By Class</h2>
<?php Part::draw('code/table', $controller, $classes);?>

<a href="<?php echo $controller->urlTo('index'); ?>">Re-index your Code</a>