<?php
/*== Part Inputs ==*/
// selected: The name of the selected navigation item
assert(is_string($selected));
/*=================*/

function printNavItem($action, $display, $selectedUrl) {
	if($selectedUrl == $display) $liClass = ' class="highlight"';
	else $liClass = '';
	echo '<li' . $liClass . '>' . Html::anchor(Url::action($action),$display) . '</li>';
}
?>
<div class="span-19 navigation last">
	<ul>
	<?php printNavItem('RecessToolsAppsController::home', 'Apps', $selected) ?>
	<?php printNavItem('RecessToolsDatabaseController::home', 'Database', $selected) ?>
	<?php printNavItem('RecessToolsCodeController::home', 'Code', $selected) ?>
	<?php printNavItem('RecessToolsRoutesController::home', 'Routes', $selected) ?>
	</ul>
</div>