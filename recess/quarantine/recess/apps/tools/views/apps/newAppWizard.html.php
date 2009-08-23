<?php
Layout::extend('layouts/apps');
$title = 'New Application Instructions';
?>


<h1>New Application Walkthrough</h1>

<p>Ready to start a new application? Great! This walkthrough is designed to step you through the process.</p>

<?php $form->begin(); ?>

<h2 class="bottom">What's the human readable name?</h2>
<p class="bottom">Examples: My Awesome Web Log, Recess Tools!, Customer Backend, Store Front-End</p>
<?php $form->input('appName'); ?>

<h2 class="bottom">What's the programmatic name?</h2>
<p class="bottom">Examples: MyWebLog, RecessTools, Backend, StoreFront</p>
<?php $form->input('programmaticName'); ?>

<input type="submit" value="Next Step" /><br /><br />

<?php $form->end(); ?>