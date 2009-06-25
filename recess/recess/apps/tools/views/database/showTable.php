<?php
Layout::extend('layouts/database');
$title = $sourceName . ':' . $table;
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
		Part::draw( 'each-toggle', 
					$columns, 
					Part::block('database/row', ''),
					Part::block('database/row', 'light')
					); ?>
	</tbody>
</table>
<hr />
<h3><a href="<?php echo $controller->urlTo('emptyTable',$sourceName,$table); ?>">Empty Table</a> - <a href="<?php echo $controller->urlTo('dropTable', $sourceName, $table); ?>">Drop Table</a></h3>