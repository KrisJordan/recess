<?php
$title = 'Data Sources';
$selectedNav = 'database';
include_once($viewsDir . 'common/header.php');
?>
<h1>Adding a New Named Data Source</h1>
<p>Recess! allows for multiple named data sources. Currently only MySQL and Sqlite are supported.</p>

<ol>
	<li><span class="highlight">Open <?php echo $_ENV['dir.documentRoot']; ?>recess-config.php</span></li>
	<li>Find the <span class="highlight">Config::$namedDataSources</span> variable.</li>
	<li>Add a new keyed entry to the $namedDataSources array based on your RDBMS:
	<ul>
		<li><strong class="highlight">MySQL</strong>: 
		<pre name="code" class="php">
Config::$namedDataSources 
	= array( 
			'NAME' => array(
				'mysql:host=localhost;dbname=YOUR_DB_NAME',
				'MYSQL_USERNAME',
				'MYSQL_PASSWORD'),
			);
		</pre>
		</li>
		<li><strong class="highlight">Sqlite</strong>: 
		<pre name="code" class="php">
Config::$namedDataSources 
	= array(
			'NAME' => array('sqlite:PATH/TO/YOUR/DATABASE/database.db'),
			);
		</pre>
		</li>
	</ul>
	</li>
	<li>Save.</li>
</ol>




<hr />
<h3><a href="<?php echo $controller->urlToMethod('home'); ?>">Return to Data Sources</a></h3>
<?php include_once($viewsDir . 'common/footer.php'); ?>