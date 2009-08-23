<?php
Part::input($first, 'string');
Part::input($second, 'int');
Part::input($third, 'string');

for($i = 0; $i < $second; $i++) {
	echo $first . $third;
}
?>