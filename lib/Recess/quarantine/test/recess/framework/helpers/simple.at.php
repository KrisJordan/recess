<?php
 AssertiveTemplate	::	input	( $title , 'string' ) ;
AssertiveTemplate::input($aBlock, 'Block');
AssertiveTemplate	:: input (	$max,	'int',	1	)	;

echo "$block\n";
for($i = 0; $i < $max; $i++) {
	echo "$title\n";
}
?>
