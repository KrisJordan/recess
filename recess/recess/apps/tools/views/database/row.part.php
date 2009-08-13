<?php 
Part::input($class, 'string');
Part::input($column, 'RecessColumnDescriptor');
?>
<tr<?php if($class != '') {echo " class=\"$class\"";}?>>
	<td><?php echo $column->isPrimaryKey ? 'Yes' : ''; ?></td>
	<td><?php echo $column->name; ?></td>
	<td><?php echo $column->type; ?></td>
	<td><?php echo $column->nullable ? 'Y' : 'N'; ?></td>
	<td><?php echo $column->defaultValue == '' ? 'N/A' : $column->defaultValue; ?></td>
</tr>