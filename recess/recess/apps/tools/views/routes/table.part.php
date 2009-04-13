<?php
assert($routes instanceof RtNode);
assert($codeController instanceof Controller);
assert(is_string($omit));

$fullPath = $_ENV['url.base'];
if(strrpos($fullPath, '/') == strlen($fullPath) - 1) $fullPath = substr($fullPath,0,-1);
if(!isset($omit)) $omit = '';
?>
<table>
<thead><td>HTTP</td><td>Route</td><td>Controller</td><td>Method</td></thead>
<tbody>
	<?php Part::render('routes/rows', $routes, $codeController, $fullPath, $omit) ?>
</tbody>
</table>