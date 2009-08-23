<?php
Part::input($action, 'string');
Part::input($display, 'string');
Part::input($selectedUrl, 'string');

if($selectedUrl == $display)  {
	$liClass = ' class="highlight"';
} else {
	$liClass = '';
}

echo '<li' . $liClass . '>' . Html::anchor(Url::action($action),$display) . '</li>';
?>