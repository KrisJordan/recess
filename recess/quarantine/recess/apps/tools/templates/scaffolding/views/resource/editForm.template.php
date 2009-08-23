<?php 
Layout::extend('layouts/{{modelNameLower}}');
if(isset(${{modelNameLower}}->{{primaryKey}})) {
	$title = 'Edit {{modelName}} #' . ${{modelNameLower}}->{{primaryKey}};
} else {
	$title = 'Create New {{modelName}}';
}
$title = $title;
?>

<?php Part::draw('{{modelNameLower}}/form', $_form, $title) ?>

<?php echo Html::anchor(Url::action('{{modelName}}Controller::index'), '{{modelName}} List') ?>