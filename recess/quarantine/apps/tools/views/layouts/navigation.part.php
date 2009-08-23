<?php
/*== Part Inputs ==*/
// selected: The name of the selected navigation item
Part::input($selected, 'string');
/*=================*/
?>
<div class="span-19 navigation last">
	<ul>
	<?php Part::draw('layouts/navigation-item',
						'RecessToolsAppsController::home',
						'Apps',
						$selected) ?>
	<?php Part::draw('layouts/navigation-item',
						'RecessToolsDatabaseController::home',
						'Database',
						$selected) ?>
	<?php Part::draw('layouts/navigation-item',
						'RecessToolsCodeController::home',
						'Code',
						$selected) ?>
	<?php Part::draw('layouts/navigation-item',
						'RecessToolsRoutesController::home',
						'Routes',
						$selected) ?>
	</ul>
</div>