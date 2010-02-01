<?php
Part::input($items, 'array');
Part::input($block, 'Block');

foreach($items as $item) {
	$block->draw($item);
}
?>