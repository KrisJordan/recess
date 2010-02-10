<?php include "lib/Recess/Recess.php"; use Recess\Core\Hash;

// ==== Use a Hash like PHP's built-in array
$hash = new Hash(array('key' => 'value'));
$hash[] = 'foo';
$hash['bar'] = 'baz';
echo $hash['key'],"\n"; 
//> value

// ==== Construct Hash with values
$hash = new Hash(1,2,3,4);
echo "$hash[0] $hash[1] $hash[2] $hash[3]\n";
//> 1 2 3 4

// ==== Use higher-order methods
$hash->map(function($x) { return $x*$x; })
     ->filter(function($x) { return $x % 2 === 0; })
     ->each(function($x) { echo "$x "; });
//> 4 16
$sum = $hash->reduce(function($x,$y) { return $x + $y; }, 1);
echo "\nSum: $sum\n";
//> Sum: 10