<?php
Part::input($items, 'array');
Part::input($part, 'Block');
try {
	foreach($items as $item) {
		$part->draw($item);
	}
} catch(RecessFrameworkException $e) {
	throw new RecessFrameworkException($e->getMessage(), 1);
}
?>