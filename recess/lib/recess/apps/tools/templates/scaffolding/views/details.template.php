<?php include_once($viewsDir . 'common/header.php'); ?>
<h3>{{modelName}} <?php echo ' #', ${{modelNameLower}}->{{primaryKey}}; ?></h3>

<form method="POST" action="<?php echo $controller->urlTo('delete', ${{modelNameLower}}->{{primaryKey}}); ?>">
	<fieldset>
<p>
{{fields}}</p>
<a href="<?php echo $controller->urlTo('editForm', ${{modelNameLower}}->{{primaryKey}}); ?>">Edit</a></strong>  - 
		<input type="hidden" name="_METHOD" value="DELETE" />
		<input type="submit" name="delete" value="Delete" />
	</fieldset>
</form>
<hr />
<a href="<?php echo $controller->urlTo('index'); ?>">Back to list of {{modelName}}s</a>
<hr />
<?php include_once($viewsDir . 'common/footer.php'); ?>