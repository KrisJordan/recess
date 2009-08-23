<?php
Part::input($routes, 'RtNode');
Part::input($omit, 'string');

$fullPath = $_ENV['url.base'];
if(strrpos($fullPath, '/') == strlen($fullPath) - 1) $fullPath = substr($fullPath,0,-1);
if(!isset($omit)) $omit = '';
?>
<table>
<thead><td>HTTP</td><td>Route</td><td>Controller</td><td>Method</td></thead>
<tbody>
	<?php Part::draw('routes/rows', $routes, $fullPath, $omit) ?>
</tbody>
</table>