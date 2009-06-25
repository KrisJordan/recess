<?php
Layout::extend('layouts/apps');
$title = 'Model Created';
?>
<h1>Creating <strong><?php echo $modelName; ?></strong> Model...</h1>

<h3 class="bottom">Code Gen <?php if($modelWasSaved){ echo '<span class="added">Done</a>'; } else { echo '<span class="highlight">Almost Done</span>'; } ?></h3>
<?php if($modelWasSaved): ?>
<p>The <strong><?php echo $modelName; ?></strong> model was <span class="added">successfully saved</span> to <span class="added"><?php echo $path; ?></span>. If you'd like to take a peak at the generated code, expand the source below.</p>
<?php else:?>
<p><strong><?php echo $modelName; ?></strong> could not be saved. <?php echo $codeGenMessage; ?></p>
<p>To finish adding your model please <span class="highlight">save the code below</span> to the file <span class="highlight"><strong><?php echo $path; ?></strong></span>.</p>
<?php endif; ?>
<pre name="code" class="php<?php if($modelWasSaved) echo ':collapse'; ?>">
<?php echo str_replace('<','&lt;',$modelCode); ?>
</pre>

<h3>Table Gen 
<?php
if(!$tableGenAttempted) {
	echo ' - Skipped';		
} else {
	if($tableWasCreated) {
		echo '<span class="added">Done</a>';
	} else {
		echo '<span class="highlight">Almost Done</span>';
	}
}
?></h3>

<?php if($tableGenAttempted): ?>
<pre name="code" class="sql<?php if($tableWasCreated) echo ':collapse'; ?>">
<?php echo str_replace('<', '&lt;', $tableSql); ?>
</pre>
<?php endif; ?>
<h2><span class="highlight">Next Steps</span></h2>

<h3><a href="<?php echo $controller->urlTo('generateScaffolding', $appName, $modelName); ?>">Generate Scaffolding</a> | <a href="<?php echo $controller->urlTo('app', $appName); ?>">Back to Code</a></h3>