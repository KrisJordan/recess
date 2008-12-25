<?php
$title = 'Recess! Tools! - Home';
$selectedNav = '';
include_once($viewsDir . 'common/header.php');
?>

<h1>Welcome to Recess!</h1>

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

<?php include_once($viewsDir . 'common/footer.php'); ?>