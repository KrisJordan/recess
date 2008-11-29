<?php
$title = 'Code Browsing';
$selectedNav = 'code';
include_once($viewsDir . 'common/header.php');
?>

<h1>Code Browser</h1>
<h2>Browse by: <a href="<?php echo $controller->urlToMethod('byClass'); ?>">Class</a>, <a href="<?php echo $controller->urlToMethod('byPackage'); ?>">Package</a></h2>

<?php include_once($viewsDir . 'common/footer.php'); ?>