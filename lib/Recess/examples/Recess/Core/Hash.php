<?php include "lib/Recess/Recess.php"; use Recess\Core\Hash;

$hash = new Hash(array('key' => 'value'));
echo $hash['key']; // output: value

$hash = new Hash(1,2,3,4);
print_r($hash->map(function($x) { return $x*$x; }));
// array (1,4,9,16);

$hash = new Hash(1,2,3);
foreach($hash as $element) {
  echo "$element,";
}
// 1,2,3,