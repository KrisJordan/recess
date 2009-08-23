<?php 
Layout::extend('layouts/{{modelNameLower}}');
$title = 'Details of {{modelName}} #' . ${{modelNameLower}}->{{primaryKey}} ;
?>

<?php Part::draw('{{modelNameLower}}/details', ${{modelNameLower}}) ?>

<?php echo Html::anchor(Url::action('{{modelName}}Controller::index'), 'Back to list of {{modelNameLower}}s') ?>
<hr />