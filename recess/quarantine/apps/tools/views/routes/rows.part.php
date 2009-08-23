<?php
Part::input($routingNode, 'RtNode');
Part::input($fullPath, 'string');
Part::input($omit, 'string');

if($omit != '' && strpos($fullPath, $omit) === 0) {
	return;
}

if($routingNode == null) return;
$staticPaths = $routingNode->getStaticPaths();
$parametricPaths = $routingNode->getParametricPaths();
$methods = $routingNode->getMethods();
if(!empty($methods)) {
	foreach($methods as $method => $rt): 
		$route = $rt->toRoute();
		?>
		<tr>
		<td><?php echo $method ?></td>
		<td><?php echo $fullPath ?></td>
		<td><?php echo Html::anchor(Url::action('RecessToolsCodeController::classInfo', $route->class), Library::getClassName($route->class)); ?></td>
		<td><?php echo Html::anchor(Url::action('RecessToolsCodeController::classInfo', $route->class) . '#method_' . $route->function, $route->function) ?></td>
		</tr>
	<?php 
	endforeach;
} 

if(!empty($staticPaths) || !empty($parametricPaths)) {
	ksort($staticPaths);
	ksort($parametricPaths);
	foreach($staticPaths as $path => $node) {
		Part::draw('routes/rows', $node, $fullPath . '/' . $path, $omit);
	}
	foreach($parametricPaths as $path => $node) {
		Part::draw('routes/rows', $node, $fullPath . '/$' . $path, $omit);
	}
}
?>

