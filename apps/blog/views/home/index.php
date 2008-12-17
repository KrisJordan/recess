<?php 
$title = 'Home';
include_once($viewsDir . 'common/header.php');
?>
<div class="span-24 last">
<div class="span-12 notice">
<p>Friends,</p>
<p>I appreciate your interest in Recess! I hope you'll find these bits (somewhat) functional and fun.</p>
<p>The Recess framework is early stage so please travel with caution.</p>
<p>Thanks and Enjoy!</p>
<p><a href="http://www.krisjordan.com/">Kris Jordan</a></p>
</div>
</div>

<h2>Welcome to your new Recess application!</h2>

<h3>Next steps?</h3>

<ul>
	<li><a href="<?php echo $_ENV['url.base']; ?>recess/">Recess Tools</a></li>
</ul>

<h3>For more information visit <a href="http://www.recessframework.org/">http://www.recessframework.org/</a></h3>

<?php include_once($viewsDir . 'common/footer.php'); ?>