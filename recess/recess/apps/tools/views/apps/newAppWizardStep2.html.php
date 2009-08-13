<?php
Layout::extend('layouts/apps');
$title = 'New Application';
?>


<h1>New Application Walkthrough - Step 2</h1>

<?php $form->begin(); ?>

<h2 class="bottom">What's the url prefix to this app?</h2>
<p class="bottom">Examples: http://<?php echo $_SERVER['SERVER_NAME'], $_ENV['url.base']; ?><strong>bar/</strong></p>
<?php $form->input('routingPrefix'); ?>

<?php $form->input('appName'); ?>
<?php $form->input('programmaticName'); ?>


<input type="submit" value="Next Step" /><br /><br />

<?php $form->end(); ?>