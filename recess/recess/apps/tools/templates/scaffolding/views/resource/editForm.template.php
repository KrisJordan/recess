<?php 
Layout::extend('{{modelNameLower}}/layout');
if(isset(${{modelNameLower}}->{{primaryKey}})) {
	$title = 'Edit {{modelName}} #' . ${{modelNameLower}}->{{primaryKey}};
} else {
	$title = 'Create New {{modelName}}';
}
Layout::blockAssign('title', $title);
?>

<?php Part::render('{{modelNameLower}}/form', $_form, $title) ?>

<?php echo Html::anchor(Url::action('{{modelName}}Controller::index'), '{{modelName}} List') ?>