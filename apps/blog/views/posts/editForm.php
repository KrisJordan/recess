<?php include_once($viewsDir . 'common/header.php'); ?>

<?php

$form->begin();

echo '<br />';

echo 'Title: ';
$form->input('title');

echo '<br />';

echo 'Body: ';
$form->input('body');

echo '<br />';

echo 'Is Public: ';
$form->input('isPublic');

echo '<br />';

echo 'Modified: ';
$form->input('modifiedAt');

echo '<br />';

echo 'Created: ';
$form->input('createdOn');

echo '<br />';

echo '<input type="submit" />';

$form->end;

?>

<a href="<?php echo $controller->urlTo('index'); ?>">Show All Posts</a>

<?php include_once($viewsDir . 'common/footer.php'); ?>