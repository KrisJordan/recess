<?php
Part::input(${{modelNameLower}}, '{{modelName}}');
?>
<form method="POST" action="<?php echo Url::action('{{modelName}}Controller::delete', ${{modelNameLower}}->{{primaryKey}}) ?>">
	<fieldset>
	<h3><?php echo Html::anchor(Url::action('{{modelName}}Controller::details', ${{modelNameLower}}->{{primaryKey}}), '{{modelName}} #' . ${{modelNameLower}}->{{primaryKey}}) ?></h3>
	<p>
{{fields}}
	</p>
	<?php echo Html::anchor(Url::action('{{modelName}}Controller::editForm', ${{modelNameLower}}->{{primaryKey}}), 'Edit') ?> - 
	<input type="hidden" name="_METHOD" value="DELETE" />
	<input type="submit" name="delete" value="Delete" />
	</fieldset>
</form>