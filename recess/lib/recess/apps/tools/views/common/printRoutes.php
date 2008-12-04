<?php
function printRoutes($routes, $codeController) {
	echo '<table>';
	echo '<thead><td>HTTP</td><td>Route</td><td>Controller</td><td>Method</td></thead>';
	echo '<tbody>';
	$fullPath = $_ENV['url.base'];
	if(strrpos($fullPath, '/') == strlen($fullPath) - 1) $fullPath = substr($fullPath,0,-1);
	printRoutesRecursive($codeController, $routes, $fullPath);
	echo '</tbody>';
	echo '</table>';
}

function printRoutesRecursive($codeController, $routingNode, $fullPath) {
	static $i = 0;
	if($routingNode == null) return;
	$staticPaths = $routingNode->getStaticPaths();
	$parametricPaths = $routingNode->getParametricPaths();
	$methods = $routingNode->getMethods();
	if(!empty($methods)) {
		foreach($methods as $method => $rt) {
			$route = $rt->toRoute();
			$i++;
			if($i % 2 == 0) {
				echo '<tr class="light">';
			} else {
				echo '<tr>';
			}
			echo '<td>' . $method . '</td>';
			echo '<td>' . $fullPath . '</td>';
			echo '<td><a href="' . $codeController->urlToMethod('classInfo',$route->class) . '">' . Library::getClassName($route->class) . '</a></td>';
			echo '<td><a href="' . $codeController->urlToMethod('classInfo',$route->class) . '#method_' . $route->function . '">'. $route->function . '</a></td>';
			echo '</tr>';
		}		
	} 

	if(!empty($staticPaths) || !empty($parametricPaths)) {
		ksort($staticPaths);
		ksort($parametricPaths);
		foreach($staticPaths as $path => $node) {
			printRoutesRecursive($codeController, $node, $fullPath . '/' . $path);
		}
		foreach($parametricPaths as $path => $node) {
			printRoutesRecursive($codeController, $node, $fullPath . '/$' . $path);
		}
	}
}
?>