<?php
Layout::extend('layouts/code');
Layout::blockAssign('title', 'Code Browser');
?>
<h1>Code Browser</h1>
<?php if(count($packages) == 0):?>
	<h3><a href="<?php echo $controller->urlTo('index'); ?>">First time here? Index your code. (Note: this can take a few, please be patient!)</a></h3>
<?php endif; ?>
<h2>By Package</h2>
<?php printTable($controller, $packages); ?>
<h2>By Class</h2>
<?php printTable($controller, $classes);?>
<?php 
function printTable($controller, $objects, $columns = 4) { 
	$count = $objects->count();
	$perColumn = ceil($count / $columns);
	echo '<table>';
	for($row = 0 ; $row < $perColumn ; $row++) {
		echo '<tr>';
		for($col = 0 ; $col < $columns ; $col++) {
			$object = isset($objects[$perColumn*$col+$row]) ? $objects[$perColumn*$col+$row] : '';
			$value = is_object($object) ? $object->name : '';
			if($object instanceof RecessReflectorClass && $object->package() != null) {
				$prefix = $object->package()->name . '.';
			} else {
				$prefix = '';
			}
			$linkTo = get_class($object) == 'RecessReflectorClass' ? 'class' : 'package';
			echo '<td><a href="', $controller->urlTo($linkTo . 'Info', $prefix . $value),'">', $value, '</a></td>';
		}
		echo '</tr>';
	}
	echo '</table>';
}
?>

<a href="<?php echo $controller->urlTo('index'); ?>">Re-index your Code</a>