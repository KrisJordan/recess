<?php 
Layout::extend('{{modelNameLower}}/layout');
Layout::blockAssign('title', 'Details of {{modelName}} #' . ${{modelNameLower}}->{{primaryKey}} );
?>

<?php Part::render('{{modelNameLower}}/details', ${{modelNameLower}}) ?>

<?php echo Html::anchor(Url::action('{{modelName}}Controller::index'), 'Back to list of {{modelNameLower}}s') ?>
<hr />