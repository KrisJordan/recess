<?php function printNavItem($selectedUrl, $urlName, $displayName) {
		if($selectedUrl == $urlName) $liClass = ' class="highlight"';
		else $liClass = '';
		echo '<li' . $liClass . '><a href="' . $_ENV['url.base'] . 'recess/' . $urlName . '">' . $displayName . '</a></li>';
	}
?>
<div class="span-19 navigation last">
	<ul>
		<?php
		if(!isset($selectedNav)) $selectedNav = '';
		printNavItem($selectedNav, 'apps', 'Apps');
		printNavItem($selectedNav, 'database', 'Database');
		printNavItem($selectedNav, 'code', 'Code');
		printNavItem($selectedNav, 'routes', 'Routes');
		printNavItem($selectedNav, 'tests', 'Tests');
		?>
	</ul>
</div>