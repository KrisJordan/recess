<?php include_once($viewsDir . 'common/header.php'); ?>

<?php $_form->begin(); ?>
<fieldset>
		<legend>Edit {{modelName}}</legend>
		
		<?php $_form->input('{{primaryKey}}'); ?>		
{{editFields}}<input type="submit" value="Save" />
	</fieldset>
<?php $_form->end(); ?>

<a href="<?php echo $controller->urlTo('index'); ?>">{{modelName}} List</a>

<?php include_once($viewsDir . 'common/footer.php'); ?>