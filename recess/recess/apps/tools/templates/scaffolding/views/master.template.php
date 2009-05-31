<html>
	<head>
		<?php 
		Layout::slot('style');
			Part::render('parts/style');
		Layout::slotEnd();		
		?>
		<title>{{appName}} - <?php Layout::slot('title'); Layout::slotEnd() ?></title> 
	</head>
	<body>
	<div class="container">
		<div class="span-24">
			<h1>{{appName}}</h1>
		</div>
		<div class="span-24 last">
			<div class="navigation">
			<?php
			Layout::slot('navigation');
			Layout::slotEnd();
			?>
			</div>
			<?php 
			Layout::slot('body');
			Layout::slotEnd();
			?>
		</div>
		<div class="span-24 footer">
		  <p class="quiet bottom">
		  	 <?php echo Html::anchor('/{{camelProgrammaticName}}/', '{{appName}}') ?> is &copy; <?php echo date('Y'); ?>
		 	 {Insert Kick-ass App Developer Name Here}. All rights reserved.
		  </p>
		</div>
		</div>
	</body>
</html>