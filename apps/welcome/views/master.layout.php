<?php
Layout::input($title, 'string');
Layout::input($body, 'Block');
?>
<html>
	<head>
		<!-- Blue Print -->
		<?php echo Html::css('blueprint/screen', 'screen') ?>
		<?php echo Html::css('blueprint/print', 'print') ?>
		<!--[if IE]>
			<?php echo Html::css('blueprint/ie', 'screen') ?>
		<![endif]-->
		<title><?php if(isset($title)) echo $title; else echo 'Recess!'; ?></title>
		<style type="text/css">
			.error,.notice,.success{ margin: 0 0 1em 0; padding: 0.8em; border: 2px solid #000; }
			.error{ background: #FBE3E4; border-color: #FBC2C4; }
				.error, .error a{ color: #8A1F11; }
			.notice{ background: #FFF6BF; border-color: #FFD324; }
				.notice, .notice a{ color: #514721; }
			.success{ background: #E6EFC2; border-color: #C6D880; }
				.success, .success a{ color: #264409; }
			body { font-size: .8em; }
			p { font-size: 1.2em; }
			form{ margin: 0; padding: 0; }
			fieldset{ margin: 0 0 1em 0; padding: 1em; border: 2px solid #a9b5c7; background: #f0f6fe; }
			legend{ margin: 0; padding: 0 0.5em; font-size: 1.5em; font-weight: bold; color: #222; }
			label{ font-weight: bold; color: #222; }
			
			input.text,textarea{ width: 300px; padding: 3px; font-size: 14px; font-family: arial, verdana, sans-serif; color: #333; }
			input.short{ width: 100px; }
			input.long{ width: 500px; }
			textarea{ width: 500px; height: 150px; }
			.navigation { font-size: 1.6em; }
			.navigation ul { margin: 0 0 1em 0; }
			.navigation ul li { display: inline; background: #E6EFC2; border: 2px solid #C6D880; }
			.navigation ul li a { color: #000; text-decoration: none; margin: .5em; }
		</style> 
	</head>
	<body>
	<div class="container">
		<div class="span-24">
			<h1><?php echo $title ?></h1>
		</div>
		<div class="span-24 last">
			<?php echo $body ?>
		</div>
	</body>
</html>