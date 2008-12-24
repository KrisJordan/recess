<?php include_once($viewsDir . 'common/header.php'); ?>

<h3><a href="<?php echo $controller->urlTo('newForm'); ?>">Create New {{modelName}}</a></h3>

<?php if(isset($flash)): ?>
<div class="error">
<?php echo $flash; ?>
</div>
<?php endif; ?>

<?php foreach(${{modelNameLower}}Set as ${{modelNameLower}}): ?>

<form method="POST" action="<?php echo $controller->urlTo('delete', ${{modelNameLower}}->{{primaryKey}}); ?>">
	<fieldset>
		<h3><a href="<?php echo $controller->urlTo('details', ${{modelNameLower}}->{{primaryKey}}); ?>">{{modelName}} <?php echo ' #', ${{modelNameLower}}->{{primaryKey}}; ?></a></h3>
		<p>
{{fields}}		</p>
		<a href="<?php echo $controller->urlTo('editForm', ${{modelNameLower}}->{{primaryKey}}); ?>">Edit</a></strong>  - 
		<input type="hidden" name="_METHOD" value="DELETE" />
		<input type="submit" name="delete" value="Delete" />
	</fieldset>
</form>
<hr />
<?php endforeach; ?>

<?php include_once($viewsDir . 'common/footer.php'); ?>