<?php
Layout::input($string, 'string');
Layout::input($int, 'int');
Layout::input($object, 'ReallyObnoxiousClass');

if($string == 'foo' && $int == 1 && get_class($object) == 'ReallyObnoxiousClass') {
	echo 'great success';
} else {
	echo 'fail.';
}
?>