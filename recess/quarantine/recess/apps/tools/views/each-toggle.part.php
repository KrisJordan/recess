<?php
Part::input($items, 'array');
Part::input($even, 'Block');
Part::input($odd, 'Block');

$i = 0;
foreach($items as $item) {
	if($i++ % 2 == 0) {
		$even->draw($item);
	} else {
		$odd->draw($item);
	}
}

?>