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
		<title><?php if(isset($title)) echo $title; else echo 'Recess!'; ?></title>
	</head>
	<body>
	<div class="container">
		<div class="span-24">
			<h1>Blog</h1>
		</div>
		<div class="span-19">
			<?php include_once($viewsDir . 'common/navigation.php'); ?>