<?php include "lib/Recess/Recess.php"; use Recess\Core\Wrappable;

// ==== Short Circuit Returns
$disarm = new Wrappable(function($timeToDisarm) {
	print("disarming... ");
	if($timeToDisarm) { return "silence\n"; }
	else { return "BOOM!\n"; }
});
print($disarm(true));
//> disarming... silence
print($disarm(false));
//> disarming... BOOM!

$disarm->wrap(function($disarm,$timeToDisarm) {
	if(!$timeToDisarm) { return "RUN!\n"; }
	else return $disarm($timeToDisarm);
});
print($disarm(true));
//> disarming... silence
print($disarm(false));
//> RUN!

// ==== Automatic Unwrapping
// If a wrapper does not return or returns null, the unwrapping is not short-circuited.
$auto = new Wrappable(function() { echo "Wrapped called!\n"; });
$auto->wrap(function($wrapped) { echo "I didn't call wrapped!\n"; });
$auto();
//> I didn't call wrapped!
//> Wrapped called!

// ==== Automatic Argument Passing
$print = new Wrappable(function($text) { print($text); });
$print->wrap(
	function($print, $text) 
		{ $print();}
);
$print("I'm passed implicitly if no args are passed explicitly.\n");
//> I'm passed implicitly if no args are passed explicitly.