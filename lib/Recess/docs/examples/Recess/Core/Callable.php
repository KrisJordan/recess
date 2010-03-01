<?php include "lib/Recess/Recess.php"; use Recess\Core\Callable;

// ==== Native PHP Functions
$printf = new Callable('printf');
$printf("PHP's printf()\n");
//> PHP's printf()

// ==== User-defined Functions
function add($a,$b) { return $a + $b; }
$add = new Callable('add');
print($add(1,1) . "\n");
//> 2

// ==== Class Methods
class Foo {
	function bar() { echo "bar\n"; }
	static function baz() { echo "baz\n"; }
}
$foo = new Foo();
$foobar = new Callable(array($foo,'bar'));
$foobar();
//> bar
$foobaz = new Callable(array('Foo','baz'));
$foobaz();
//> baz
$foobaz = new Callable('Foo::baz');
$foobaz();
//> baz

// ==== Closures
$callable = new Callable(
	function($value = 'Closure') { 
		print("$value!\n"); 
	}
);
$callable();
//> Closure!

// ==== call() & apply()
$callable->call('Call method');
//> Call method!
$callable->apply(array('Apply method'));
//> Apply method!
