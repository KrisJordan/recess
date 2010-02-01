<?php
Layout::input($string, 'string');
Layout::input($number, 'int');
$string .= ' master';
for($i = 0; $i < $number ; $i++) {
	echo $string;
}
?>