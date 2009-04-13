<?php
Layout::extend('layouts/database');
Layout::blockAssign('title', $sourceName . ':' . $table);
?>
<h1>Table: <strong><?php echo $table;?></strong></h1>
<h2>Source: <?php echo $sourceName;?></h2>
<h2>Columns:</h2>

<table>
<thead>
	<tr>
		<td>Primary</td>
		<td>Column Name</td>
		<td>Type</td>
		<td>Nullable</td>
		<td>Default Value</td>
	</tr>
</thead>
<tbody>
<?php 
$i = 0;
foreach($columns as $column): 
$i++;
if($i % 2 == 0) {
	echo '<tr class="light">';
} else {
	echo '<tr>';
}
?>
		<td><?php echo $column->isPrimaryKey ? 'Yes' : ''; ?></td>
		<td><?php echo $column->name; ?></td>
		<td><?php echo $column->type; ?></td>
		<td><?php echo $column->nullable ? 'Y' : 'N'; ?></td>
		<td><?php echo $column->defaultValue == '' ? 'N/A' : $column->defaultValue; ?></td>
	</tr>
<?php endforeach ?>
</tbody>
</table>
<hr />
<h3><a href="<?php echo $controller->urlTo('emptyTable',$sourceName,$table); ?>">Empty Table</a> - <a href="<?php echo $controller->urlTo('dropTable', $sourceName, $table); ?>">Drop Table</a></h3>