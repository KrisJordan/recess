<html>
<head><title>Model <?php echo $reflection->name; ?></title></head>
<body>
<?php
$parent = $reflector->parent();
$parentPackage = $parent->package();
$children = $reflector->children();
?>
<h3><a href="../package/<?php echo $reflector->package()->name;?>"><?php echo $reflector->package()->name; ?></a></h3>
<h1><?php echo $reflection->name; ?></h1>
<h2>Subclass of: <a href="../package/<?php echo $parentPackage->name; ?>"><?php echo $parentPackage->name; ?></a>.<a href="<?php echo $parentPackage->name . '.' . $parent->name; ?>"><?php echo $parent->name; ?></a></h2>
<p><?php echo nl2br($reflection->getDocComment()); ?></p>
<?php
if(!empty($children)) { ?>
<h2>Subclasses</h2>
<?php
}
foreach($children as $child) {
	?><li><a href="<?php echo $child->package()->name . '.' . $child->name ?>"><?php echo $child->name; ?></a><?php
}
?>

<div class="orm">
<h2>Model Info</h2>
<h3>Table: <?php echo $table; ?></h3>
<h3>Source: <?php echo $source; ?></h3>
<h3>Relationships</h3>
<ul class="relationships">
<?php

foreach($relationships as $relationship) { ?>
	
	<li><span class="relationship-type"><?php echo $relationship->getType(); ?></span>
		<?php echo $relationship->name; ?>, Class: 
		<a href="<?php echo $relationship->foreignClass; ?>"><?php echo $relationship->foreignClass; ?></a>
		<ul class="relationship-details">
			<li>ForeignKey: <?php echo $relationship->foreignKey; ?></li>
			<li>OnDelete: <?php echo ($relationship->onDelete == 'unspecified') ? $relationship->getDefaultOnDeleteMode() : $relationship->onDelete; ?></li>
			<?php if($relationship->through != '') { ?>
			<li>Through: <?php echo $relationship->through; ?></li>
			<?php } ?>
		</ul>
	</li>
	
<?php
}

?>
</ul>
<h3>Columns</h3>
<ul class="columns">
<?php
foreach($columns as $column) {
	echo '<li>' . $column . '</li>';
}
?>
</div>
<h2>Class Info</h2>
<h3>Properties</h3>
<ul class="properties">
<?php
foreach($reflection->getProperties() as $property) {
	if(!$property->isStatic() && $property->isPublic()) {
		echo '<li>' . $property->name . '</li>';
	}
}
?>
</ul>
<h3>Methods</h3>
<h4>Attached Methods</h4>
<ul class="attached-methods">
<?php
foreach($reflection->getMethods(true) as $method) {
	if($method->isAttached()) {
		echo '<li>' . $method->name . ' ( ';
		$first = true;
		foreach($method->getParameters() as $param) {
			if($first) $first = false;
			else echo ', ';
			echo '$' . $param->name;
		}
		echo ' )</li>';
	}
}
?>
</ul>
<h4>Instance Methods</h4>
<ul class="instance-methods">
<?php
foreach($reflection->getMethods(true) as $method) {
	if($method->isPublic() && !$method->isStatic() && !$method->isAttached() && $method->name != '__call') {
		echo '<li>' . $method->name . ' ( ';
		$first = true;
		foreach($method->getParameters() as $param) {
			if($first) $first = false;
			else echo ', ';
			echo '$' . $param->name;
		}
		echo ' )</li>';
	}
}
?>
</ul>
<h4>Static Methods</h4>
<ul class="static-methods">
<?php
foreach($reflection->getMethods(true) as $method) {
	if($method->isPublic() && $method->isStatic()) {
		echo '<li>' . $method->name . ' ( ';
		$first = true;
		foreach($method->getParameters() as $param) {
			if($first) $first = false;
			else echo ', ';
			echo '$' . $param->name;
		}
		echo ' )</li>';
	}
}
?>
</ul>
</body>
</html>