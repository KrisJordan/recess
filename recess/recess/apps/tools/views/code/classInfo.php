<?php
Layout::extend('layouts/code');
Layout::blockAssign('title', 'Class ' . $reflection->name);
?>

<?php
$parent = $reflector->parent();
if(isset($parent)) {
	$parentPackage = $parent->package();
}
$children = $reflector->children();
?>

<div class="span-3 border left-sidebar">
<h3>Quick Links</h3>
<ul>
	<?php if (isset($table)) { ?>
	<li class="loud">Model Info</li>
	<ul>
		<li><a href="#table">Table</a></li>
		<li><a href="#source">Source</a></li>
		<li><a href="#relationships">Relationships</a></li>
		<li><a href="#columns">Columns</a></li>
	</ul>
	<?php } ?>
	<li class="loud">Class Info</li>
	<ul>
		<li><a href="#properties">Properties</a></li>
		<li><a href="#methods">Methods</a></li>
	</ul>
</ul>
</div>
<div class="span-16 last">
<h3 class="quiet">Package: <a href="../package/<?php echo $reflector->package()->name;?>"><?php echo $reflector->package()->name; ?></a></h3>
<h1><?php echo $reflection->name; ?> 
<?php if(isset($parentPackage)) { ?>
<span class="quiet">extends <a href="<?php echo $parentPackage->name . '.' . $parent->name; ?>"><?php echo $parent->name; ?></a></span>
<?php } ?>
</h1>

<?php printSubclasses($children); ?>

<?php if (isset($table)) printModelInfo($table, $source, $relationships, $columns); ?>

<?php
function printModelInfo($table, $source, $relationships, $columns) {
 	echo '<h2>Model Info</h2>';
	printTable($table);
	printSource($source);
	printRelationships($relationships);
	printColumns($columns);
}
?>

<h2>Class Info</h2>

<a name="properties"></a>
<h3>Properties</h3>
<?php printProperties($reflection); ?>

<a name="methods"></a>
<h3>Methods</h3>
<?php 
$methods = $reflection->getMethods(true);

$instanceMethods = array();
$staticMethods = array();
$attachedMethods = array();

foreach($methods as $method) {
	if($method->isPublic()) {
		if($method->isStatic()) {
			$staticMethods[] = $method;
		} else if($method->isAttached()) {
			$attachedMethods[] = $method;
		} else {
			$instanceMethods[] = $method;
		}
	}	
}

if(!empty($attachedMethods)) {
	printAttachedMethods($attachedMethods);
}

if(!empty($instanceMethods)) {
	printInstanceMethods($instanceMethods);
}

if(!empty($staticMethods)) {
	printStaticMethods($staticMethods);
}
?>

</div>

<?php
// Print Methods


function printSubclasses($children) {
	if(!$children->isEmpty()) { ?>
	<h2>Subclasses</h2>
	<ul>
	<?php
	}
	foreach($children as $child) {
		?><li><a href="<?php echo $child->package()->name . '.' . $child->name ?>"><?php echo $child->name; ?></a><?php
	}
	echo '</ul>';
}
?>

<?php
function printTable($table) {
	echo '<a name="table"></a><h3>Table: ', $table, '</h3>';
}
function printSource($source) {
	echo '<a name="source"></a><h3>Source: ', $source, '</h3>';
}
function printRelationships($relationships) { ?>
	<a name="relationships"></a>
	<h3>Relationships</h3>
	<ul class="relationships">
	<?php
	foreach($relationships as $relationship) { ?>
		<li><span class="relationship-type"><?php echo $relationship->getType(); ?></span>
			<?php echo $relationship->name; ?>, Class: 
			<a href="<?php echo Library::getFullyQualifiedClassName($relationship->foreignClass); ?>"><?php echo $relationship->foreignClass; ?></a>
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
	echo '</ul>';
}
function printColumns($columns) {
	?>
	<a name="relationships"></a>
	<h3>Columns</h3>
	<ul class="columns">
	<?php
	foreach($columns as $column) {
		echo '<li>' . $column . '</li>';
	}?></ul><?php
}?>

<?php
function printProperties($reflection) {
	echo '<ul class="properties">';
	foreach($reflection->getProperties() as $property) {
		if(!$property->isStatic() && $property->isPublic()) {
			echo '<li>' . $property->name . '</li>';
		}
	}
	echo '</ul>';
}
?>

<?php 
function printAttachedMethods($methods) { ?>
	<h4>Attached Methods</h4>
	<ul class="attached-methods">
	<?php
	sort($methods);
	foreach($methods as $method) {
		echo '<li><a name="method_' . $method->name .'"></a>' . $method->name . ' ( ';
		$first = true;
		foreach($method->getParameters() as $param) {
			if($first) $first = false;
			else echo ', ';
			echo '$' . $param->name;
		}
		echo ' )</li>';
	}
	?>
	</ul>
<?php
}
?>

<?php function printInstanceMethods($methods) { ?>
	<h4>Instance Methods</h4>
	<ul class="instance-methods">
	<?php
	foreach($methods as $method) {
		if($method->name != '__call') {
			echo '<li><a name="method_' . $method->name .'"></a>' . $method->name . ' ( ';
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
<?php } ?>

<?php
function printStaticMethods($methods) { ?>
	<h4>Static Methods</h4>
	<ul class="static-methods">
	<?php
	foreach($methods as $method) {
		echo '<li><a name="method_' . $method->name .'"></a>' . $method->name . ' ( ';
		$first = true;
		foreach($method->getParameters() as $param) {
			if($first) $first = false;
			else echo ', ';
			echo '$' . $param->name;
		}
		echo ' )</li>';
	}
	?>
	</ul>
<?php } ?>