<?php 
Layout::extend('layouts/{{modelNameLower}}');
$title = 'Index';
?>

<h3><?php echo Html::anchor(Url::action('{{modelName}}Controller::newForm'), 'Create New {{modelName}}') ?></h3>

<?php if(isset($flash)): ?>
	<div class="error">
	<?php echo $flash; ?>
	</div>
<?php endif; ?>

<?php foreach(${{modelNameLower}}Set as ${{modelNameLower}}): ?>
	<?php Part::draw('{{modelNameLower}}/details', ${{modelNameLower}}) ?>
	<hr />
<?php endforeach; ?>