<?php Layout::extend('layouts/home'); ?>
<?php $title = 'Welcome' ?>

<h1>Welcome to Recess</h1>
<?php if(get_magic_quotes_gpc()): ?>
	<div class="error">
		<h3><strong>Warning</strong>: PHP's <a href="http://us3.php.net/manual/en/security.magicquotes.php">Magic Quotes</a> Setting is Enabled!<br /></h3>
		<p><strong><a href="http://us3.php.net/manual/en/security.magicquotes.disabling.php">You should turn off magic quotes.</a></strong> It is a setting in php.ini.<br />
		Why are magic quotes bad? Here's <a href="http://us3.php.net/manual/en/security.magicquotes.whynot.php"> the official word from PHP</a>.net.</p>
	</div>
<?php endif; ?>

<h2>What is Tools?</h2>
<p>
Recess Tools is an application to help you along the development path. With Tools you can inspect the Recess Applications you have installed, ensure databases are connected properly, and browse code. 
</p>

<h3><a href="<?php echo $_ENV['url.base'];?>recess/apps">Apps</a>: Browse and create apps, generate models & scaffolding, and view an app's routes.</h3>

<h3><a href="<?php echo $_ENV['url.base'];?>recess/database">Database</a>: Ensure database connectivity and perform basic DB operations.</h3>

<h3><a href="<?php echo $_ENV['url.base'];?>recess/code">Code</a>: Introspect your project's code.</h3>

<h3><a href="<?php echo $_ENV['url.base'];?>recess/routes">Routes</a>: View routes for all active applications.</h3>

<h2>Looking for other Tools?</h2>
<p>
As Recess matures the tool set will need to grow too. Have ideas for other useful tools? Perhaps you've built additional useful tools? Get involved over at <a href="http://www.recessframework.org/">RecessFramework.org</a>.
</p>

<h3>Enjoy!<br />-<a href="http://www.krisjordan.com/">Kris</a></h3>
