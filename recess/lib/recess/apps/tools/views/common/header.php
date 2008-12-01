<?php
$qotms = array(
			'Because it\'s no fun killing bugs without a magnifying glass.',
			'Because it\'s no fun building tree forts without a hammer.',
			'Because it\'s no fun building sandcastles without buckets.',
			'Because it\'s no fun digging holes without a shovel.',
			'Because it\'s no fun playing hide-and-seek alone.',
			'Because it\'s no fun finding booty without a treasure map.',
			'Because it\'s no fun fighting evil without a sling shot.',
			'Because it\'s no fun being chased by girls without a cooties shot.',
			'Because it\'s no fun playing slip \'n slide without a hose.'
);
?>

<html>
	<head>
		<!-- Blue Print -->
		<link rel="stylesheet" href="<?php echo $_ENV['url.content']; ?>css/blueprint/screen.css" type="text/css" media="screen, projection" />
		<link rel="stylesheet" href="<?php echo $_ENV['url.content']; ?>css/blueprint/print.css" type="text/css" media="print" /> 
		<!--[if IE]>
		  <link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection" />
		<![endif]-->
		<link rel="stylesheet" href="<?php echo $_ENV['url.content']; ?>css/recess.css" />
		<!-- Syntax Highlighter -->  
		<link type="text/css" rel="stylesheet" href="<?php echo $_ENV['url.content']; ?>css/SyntaxHighlighter.css"></link>
		<script language="javascript" src="<?php echo $_ENV['url.content']; ?>js/shCore.js"></script>
		<script language="javascript" src="<?php echo $_ENV['url.content']; ?>js/shBrushPhp.js"></script>
		<script language="javascript">
			window.onload = function() {
				dp.SyntaxHighlighter.ClipboardSwf = '<?php echo $_ENV['url.content']; ?>flash/clipboard.swf';
				dp.SyntaxHighlighter.HighlightAll('code');
			}
		</script>
		
		<title><?php if(isset($title)) echo $title; else echo 'Recess! Tools!'; ?></title>
	</head>
	<body>
	<div class="container">
			<div class="span-11 header">
				<h1>Recess! Tools</h1>
			</div>
			<div class="span-13 last">
				<p class="qotm quiet"><?php echo $qotms[rand(0,count($qotms)-1)]; ?></p>
			</div>
			<div class="span-19">
				<?php include_once($viewsDir . 'common/navbar.php'); ?>