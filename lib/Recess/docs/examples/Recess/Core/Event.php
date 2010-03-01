<?php include "lib/Recess/Recess.php"; use Recess\Core\Event;

// ==== One Callback, With No Arguments
$onLoad = new Event();
$onLoad->callback(function() { echo "Event triggered!\n"; });
echo 'Calling onLoad...';
$onLoad();
//> Calling onLoad... Event triggered!

// ==== Many Callbacks
$onLoad = new Event();
$onLoad->callback(function() { echo "First callback. "; })
       ->callback(function() { echo "Second callback.\n"; });
$onLoad();
//> First callback. Second callback.

// ==== Passing Arguments
$onSaveFile = new Event();
$onSaveFile->callback(function($file) { echo "Saving $file!\n"; });
$onSaveFile('example.txt');
//> Saving example.txt!