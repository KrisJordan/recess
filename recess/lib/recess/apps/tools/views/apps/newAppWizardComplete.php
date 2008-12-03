<?php
$title = 'New Application Instructions';
$selectedNav = 'apps';
include_once($viewsDir . 'common/header.php');

?>
<h1>Creating New Application...</h1>

<pre>
<?php
foreach($messages as $message) {
	echo $message, '<br />';
}
?>
</pre>

<h2><span class="highlight">Last Step</span>: Activate <span class="highlight"><?php echo $applicationFullClass; ?></span> in recess-config.php</h2>
<p>To enable your application open the Recess! config file: <span class="highlight"><?php echo $_ENV['dir.documentRoot']; ?>recess-config.php</span></p>
<p>Find the Config::$applications array and <span class="highlight">add the following application string</span>:</p>
<pre name="code" class="php:nogutter">
Config::$applications 
     = array(
         'recess.apps.tools.RecessToolsApplication',
         '<?php echo $applicationFullClass; ?>', // &lt;-- ADD THIS LINE
       );
</pre>

<h2><span class="highlight">Did you add that line?</span> Great! Have fun building <a href="<?php echo $controller->urlToMethod('app', $applicationClass); ?>"><?php echo $appName; ?></a>!</h2>

<?php
include_once($viewsDir . 'common/footer.php');
?>