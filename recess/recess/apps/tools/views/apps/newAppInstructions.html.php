<?php
Layout::extend('layouts/apps');
$title = 'New App Instructions';
?>

<h1>Your apps dir isn't writeable...</h1>

<h2>Please make: <span class="highlight"><?php echo $_ENV['dir.apps']; ?></span> writeable and try again.</h2>
