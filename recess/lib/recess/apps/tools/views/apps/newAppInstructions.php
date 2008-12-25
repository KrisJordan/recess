<?php
$title = 'New Application Instructions';
$selectedNav = 'apps';
include_once($viewsDir . 'common/header.php');
?>

<h1>Your apps dir isn't writeable...</h1>

<h2>Please make: <span class="highlight"><?php echo $_ENV['dir.apps']; ?></span> writeable and try again.</h2>

<?php
include_once($viewsDir . 'common/footer.php');
?>