<?php
Layout::extend('layouts/database');
$title = 'New Data Source';
?>
<h1>Adding a New Named Data Source</h1>
<p>Recess allows for multiple named data sources. Currently only MySQL and Sqlite are supported.</p>

<ol>
	<li><span class="highlight">Open <?php echo $_ENV['dir.bootstrap']; ?>recess-conf.php</span></li>
	<li>Find the <span class="highlight">RecessConf::$namedDatabases</span> variable.</li>
	<li>Add a new keyed entry to the $namedDatabasesarray based on your RDBMS:
	<ul>
		<li><strong class="highlight">MySQL</strong>: 
		<pre name="code" class="php">
RecessConf::$namedDatabases
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
RecessConf::$namedDatabases
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
<h3><a href="<?php echo $controller->urlTo('home'); ?>">Return to Data Sources</a></h3>