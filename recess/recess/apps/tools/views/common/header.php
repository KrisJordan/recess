<html>
	<head>
		<!-- Blue Print -->
		<?php echo html::css('blueprint/screen', 'screen'); ?>
		<?php echo html::css('blueprint/print', 'print'); ?>
		<!--[if IE]>
			<?php echo html::css('blueprint/ie', 'screen'); ?>
		<![endif]-->
		<?php echo html::css('recess'); ?>
		 
		<?php
		echo html::css('SyntaxHighlighter');
		echo html::js('shCore');
		echo html::js('shBrushPhp');
		echo html::js('shBrushSql'); 
		?>
		<script language="javascript">
			window.onload = function() {
				dp.SyntaxHighlighter.ClipboardSwf = '<?php echo $_ENV['url.content']; ?>flash/clipboard.swf';
				dp.SyntaxHighlighter.HighlightAll('code');
			}
		</script>
		<?php
		if(isset($scripts) && is_array($scripts)) {
			foreach($scripts as $script) {
				include_once($viewsDir . $script);
			}
		}
		?>
		<title><?php if(isset($title)) echo $title; else echo 'Recess Tools!'; ?></title>
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
				<?php include($viewsDir . 'common/navbar.php'); ?>