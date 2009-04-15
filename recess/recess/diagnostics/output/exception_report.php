<?php
function printType($var) {
	if(is_object($var)) print get_class($var); else print gettype($var);
}

function printSimpleValueOrType($var) {
	if(is_object($var)) print get_class($var);
	else if (is_array($var)) print 'Array';
	else if (is_string($var)) print '\'' . $var . '\'';
	else print $var;
}

function printValueOf($var) {
	static $count = 0;
	$count++;
	if($count < 8) {
		if(is_array($var)) {
			printArrayTable($var);
		} else if(is_object($var)) {
			printObjectTable($var);
		} else {
			if (is_string($var)) print '\'' . $var . '\'';
			else print $var;
		}
	} else {
		print '...';
	}
	$count--;
}

function printArrayTable($var) {
	if(!is_array($var)) return;
	if(!empty($var)) {
		print '<div class="detailstoggle">Array[' . count($var) . ']</div>';
		print '<table class="arraydetails">';
		print '<thead class="subhead"><td>Key</td><td>Value</td></thead>';
		foreach(array_keys($var) as $key) {
			print '<tr>';
			print '<td>'; print $key; print '</td>';
			print '<td>'; printValueOf($var[$key]); print '</td>';
			print '</tr>';
		}
		print '</table>';
	} else {
		print '<div>Array[' . count($var) . ']</div>';
	}
}

function printObjectTable($var) {
	if(!is_object($var)) return;
	print '<div class="detailstoggle">' . get_class($var) . '</div>';
	print '<table class="classdetails">';
	print '<thead class="subhead"><td>Member</td><td>Value</td></thead>';
	$class = new ReflectionClass(get_class($var));
	foreach(get_object_vars($var) as $key => $value) {
		print '<tr>';
		print '<td>'; print $key; print '</td>';
		print '<td>'; printValueOf($value); print '</td>';
		print '</tr>';
	}
	
//	foreach($class->getProperties() as $property) {
//		if($property->isPublic()) {
//			print '<tr>';
//			print '<td>'; print $property->getName(); print '</td>';
//			print '<td>'; printValueOf($property->getValue($var)); print '</td>';
//			print '</tr>';
//		}
//	}
	print '</table>';
}

function printCodeSnippet($file, $line) {
	$lineStart = max(array($line - 5, 0));
	$lines = file($file);
	$lineEnd = min(array($line + 5, count($lines)));
	print '<div class="code"><ul>';
	
	$lines = array_splice($lines, $lineStart, $lineEnd - $lineStart);
	$lines = implode(';;;', $lines);
	$html = highlight_string('<?php ;;;' . $lines . ';;; ?>', true);
	$lines = explode(';;;', $html);
	array_pop($lines);
	array_shift($lines);
	
	for($i = 0 ; $i < count($lines); $i++) {
		print '<li>';
		print $i + $lineStart + 1 . ':';
		if($i == $line - $lineStart - 1) print '<strong>';
		print $lines[$i];
		if($i == $line - $lineStart - 1) print '</strong>';
		print '</li>';
	}
	print '</ul></div>';
}

function printFunctionLocation($trace) {
	if(isset($trace['class'])) {
		print $trace['class']; print $trace['type']; print $trace['function'];
	} else {
		print $trace['function'];
	}
}

function printContext($context) {
	if(count($context) > 0) {
	?>
	<h2>Local Context</h2>
	<table>
		<thead>
			<td>Name</td>
			<td>Type</td>
			<td>Value</td>
		</thead> 
	<?php
	foreach(array_keys($context) as $key) { ?>
		<tr>
			<td>$<?php print $key ?></td>
			<td><?php printType($context[$key]); ?></td>
			<td><?php printValueOf($context[$key]); ?></td>
		</tr>
		<?php
	}
	print '</table>';
	}
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/XHTML1/DTD/XHTML1-strict.dtd">
<html xmlns="http://www.w3.org/1999/XHTML" xml:lang="en" lang="en" dir="ltr"> 

	<head>
		<title>Recess! diagnostics! 500 :(</title>
		<script type="text/javascript" src="<?php echo $_ENV['url.base']; ?>recess/recess/apps/tools/public/js/jquery/jquery-1.2.6.js"></script> 
		<script type="text/javascript">
			$(document).ready(function() {
				$('.callstackdetails').hide();
				
				$('.classdetails').hide();
				$('.arraydetails').hide();
				
				$('.callstacklevel').click(function() {
					$(this).parent().children('.callstackdetails').toggle();
				});
				
				$('.detailstoggle').click(function() {
					$(this).next().toggle();
				});
			});
		</script>
		<style type="text/css">
			body { background: #b2c9e7; margin: 0; padding: 0; text-align: center; font-family: Tahoma, sans-serif; }
			h1 { font-size: 2em; line-height: 2em; margin-top: 0; }
			h2 { font-size: 1.5em; line-height: 1.5em; margin-top: 0; margin-bottom: 0; }
			h3 { font-size: 1.1em; line-height: 1.2em; margin-top: 0; margin-bottom: .1em; text-decoration:underline; font-weight: normal;}
			#container { text-align: left; background: #fff; position: relative; margin: 0 .5em; border: 1px solid #039;}
			#header { background: #003399; height: 120px; }
			#logo { height: 120px; vertical-alignment: center;  width: 253px; background: url('<?php echo $_ENV['url.base']; ?>recess/recess/apps/tools/public/images/recess/RecessDiagnostics.png') left no-repeat; margin-left: 20px; display: block; position: absolute; left: 0; }
			#logo h1 { visibility: hidden; margin: 0; }
			#httpCode { height: 120px; width: 253px; vertical-alignment: center; background: url('<?php echo $_ENV['url.base']; ?>recess/recess/apps/tools/public/images/recess/500.png') right no-repeat; margin-right: 20px; display:block; position: absolute; right: 0;}
			#httpCode h2 { visibility: hidden; margin: 0;  }
			#error { margin: 1em; border: 3px solid #c03; background: #fcc; padding: 1em; }
			#error h2 { color: #cc0033; }
			#error .code { border: 1px solid #c03; }
			
			#error table { background: #fff; border-width: 1px; border: 1px solid #c03; border-collapse: collapse; font-size: 9pt; font-family: 'courier new', courier, monospace; }
			#error table thead { background: #c03; color: white; font-weight: bold; font-size: 12pt; }
			#error table thead.subhead { font-size: 10pt; }
			#error table td { border: 1px solid #c03; padding: .3em; vertical-align: top; }
			
			#callstack { margin: 1em; border: 3px solid #0f3; background: #cfc; }
			#callstack h2 { color: #cfc; background: #0f3; padding-left: 1em; margin: 0; }
			#callstack ul.thestack { list-style: none; margin: 1em; padding: 0; border-bottom: 1px solid #0f3; }
			.thestackli { border: 1px solid #0f3; border-bottom: none; background: #fff; padding: .5em 0 .5em 0; font-family: 'courier new', courier, monospace; font-size: 14pt; line-height: 15pt; }
			#callstack code { border: 1px solid #0f3; }
			.callstackdetails { margin: 1em 0 0 0; margin-left: .7em; display: block; border-left: 2em solid #cfc; padding-left: 1em;  }
			
			.code { background: white; padding: 1em; display: block; font-family: 'courier new', courier, monospace; }
			.code ul { margin: 0; padding: 0; border: none; }
			.code li { list-style: none; border: none; background: #fff; font-size: 9pt; }
			.code strong { background: yellow; }
			#callstack ul.thestack li .code { border: 1px solid #0f3; margin-right: 1em; }
			#callstack ul.thestack li .code ul { margin: 0 0 0 0; }
			#callstack ul.thestack li .code ul li { border: none; margin: 0; padding: 0; font-size: 9pt; } 
			
			.callstacklevel { background: #cfc; padding: .3em .7em; border: 1px solid #0f3; margin: 0 .5em; display: inline-block; float: left; cursor: pointer; text-decoration: underline;}
			.callstackdetailheaders { font-size: 9pt; }
			.callstackdetailheaders ul { display: block; }
			.callstackdetailheaders li { display: inline; padding: 0 1em; margin: 0 1em 0 0; list-style:none; }
			
			.function { font-weight: bold; } 
			
			#callstack table { background: #cfc; border-width: 1px; border: 1px solid #0f3; border-collapse: collapse; font-size: 9pt; }
			#callstack table thead { background: #0f3; font-weight: bold; font-size: 12pt; }
			#callstack table thead.subhead { font-size: 10pt; }
			#callstack table td { border: 1px solid #0f3; padding: .3em; vertical-align: top; }
			
			.detailstoggle { text-decoration: underline; cursor: pointer; }
			.objectdetails { }
			.arraydetails { }
		</style>
	</head>

	<body>
		<div id="container">
			<div id="header">
				<div id="logo">
					<h1 class="logo">Recess</h1>
				</div>
				<div id="httpCode">
					<h2>500 Internal Server Error</h2>
				</div>
			</div>
			<div id="body">
				<div id="error">
					<h2><?php print nl2br($exception->getMessage()); ?></h2>
					<p>Location: Line <?php print $exception->getLine(); ?> of <?php print $exception->getFile(); ?></p>
					<?php printCodeSnippet($exception->getFile(), $exception->getLine()); ?>
					<?php if($exception instanceof RecessException || $exception instanceof RecessErrorException) { printContext($exception->context); } ?>
				</div>
				
				<div id="callstack">
					<h2>Call Stack</h2>
					<ul class="thestack">
					<?php
					$i = 0;
					$exceptionTrace = array();
					if($exception instanceof RecessException || $exception instanceof RecessErrorException) {
						$exceptionTrace = $exception->getRecessTrace();	
					} else {
						$exceptionTrace = $exception->getTrace();
					}
					$level = count($exceptionTrace);
					foreach($exceptionTrace as $trace) {
						if(!isset($trace['line'])) { --$level; continue; }
						?>
						<li class="thestackli" ><div class="callstacklevel"><?php print --$level; ?></div><span class="function"><?php printfunctionLocation($trace); ?>
<?php				 	if($i >= 0) {
							print '(';
							$first = true;
							
							$argsTrace = $exceptionTrace[$i];
							if(isset($argsTrace['args']) && count($argsTrace['args']) > 0) {
								print ' ';
								foreach($argsTrace['args'] as $arg) {
									if(!$first) print ', ';
									else $first = false; 
									printSimpleValueOrType($arg);
								}
								print ' ';
							}
							print ')';
						}
						$i++;
						?>
						</span>
						<div class="callstackdetailheaders">
							<ul>
								<li>called at Line <?php if(isset($trace['line'])) print $trace['line']; ?> of <?php print $trace['file']; ?></li>
							</ul>
						</div>
						<div class="callstackdetails">
							<?php if(isset($trace['args']) && count($trace['args']) > 0) { ?>
							<h3>Arguments Passed In</h3>
							<table>
								<thead>
									<td>Type</td>
									<td>Value</td>
								</thead>
								<?php foreach($trace['args'] as $arg) { ?>
								<tr>
									<td><?php printType($arg); ?></td>
									<td><?php printValueOf($arg); ?></td>
								</tr>
								<?php } ?>
							</table>
							<?php } ?>
							<h3>Called From</h3>
							<?php printCodeSnippet($trace['file'], $trace['line']); ?>
						</div>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<div id="footer">
			</div>
		</div>
	</body>
</html>











