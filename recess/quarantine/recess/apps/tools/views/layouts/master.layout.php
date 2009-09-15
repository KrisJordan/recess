<?php
Layout::input($title, 'string');
Layout::input($body, 'Block');
Layout::input($navigation, 'Block');
Layout::input($scripts, 'Block', new HtmlBlock());
?>
<html>
	<head>
		<!-- Blue Print -->
		<?php echo Html::css('blueprint/screen', 'screen'); ?>
		<?php echo Html::css('blueprint/print', 'print'); ?>
		<!--[if IE]>
			<?php echo Html::css('blueprint/ie', 'screen'); ?>
		<![endif]-->
		<?php echo Html::css('recess'); ?>
		 
		<?php
		echo Html::css('SyntaxHighlighter');
		echo Html::js('shCore');
		echo Html::js('shBrushPhp');
		echo Html::js('shBrushSql'); 
		?>
		<script language="javascript">
			window.onload = function() {
				dp.SyntaxHighlighter.ClipboardSwf = '<?php echo Url::base('flash/clipboard.swf') ?>'
				dp.SyntaxHighlighter.HighlightAll('code');
			}
		</script>
		<?php echo $scripts ?>
		<title>Recess Tools! - <?php echo $title; ?></title>
	</head>
	<body>
	<div class="container">
			<div class="span-11 header">
				<h1>Recess Tools</h1>
			</div>
			<div class="span-13 last">
				<p class="qotm quiet">"Give us the tools, and we'll finish the job." ~Churchill</p>
			</div>
			<div class="span-19">
				<?php echo $navigation; ?>
				<?php echo $body; ?>
			</div>
			<div class="span-5 last infobar">
			  <h3><span>Recess Resources</span></h3>
			  <ul>
				<li><a href="http://www.recessframework.org/">RecessFramework.org</a></li>
				<li><a href="http://www.recessframework.org/blog">Recess Blog</a></li>
				<li><a href="http://www.krisjordan.com/">Kris' Blog</a></li>
				<li><a href="http://groups.google.com/group/recess-framework">Mailing Group</a></li>
				<li><a href="http://recess.lighthouseapp.com/">Report Bugs</a></li>
				<li><a href="http://github.com/recess/recess/">Recess Source</a></li>
			  </ul>
			</div>
			<div class="span-24 footer">
			  <p class="quiet bottom"><a href="http://www.recessframework.org/">Recess PHP Framework</a> is &copy; 2008 <a href="http://www.krisjordan.com/">Kris Jordan</a>. All rights reserved. Recess is open source under the <a href="http://www.opensource.org/licenses/mit-license.php">MIT license</a>.</p>
			</div>
		</div>
	</body>
</html>