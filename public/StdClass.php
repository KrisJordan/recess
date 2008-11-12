<?php
phpinfo();
exit;

class DynamicPropsTake1 {
	protected $properties;
	function get ($property) { return $this->properties[$property]; }
	function set ($property, $value) { $this->properties[$property] = $value; }
}

$take1 = new DynamicPropsTake1();
$take1->set('key','value');
echo 'Take 1: ' . $take1->get('key') . '<br />';
// isset($take1->get('notset'))	Fatal Error
// foreach($take1 as $property => value) Returns empty


class DynamicPropsTake2 {
	protected $properties;
	function __get($property) { return $this->properties[$property]; }
	function __set($property, $value) { $this->properties[$property] = $value; }
	function __isset($property) { return isset($this->properties[$property]); }
	function __unset($property) { unset($this->properties[$property]); }
}
$take2 = new DynamicPropsTake2();
$take2->key = 'value';
echo 'Take 2: ' . $take2->key . '<br />';
if(isset($take2->notset)) echo 'Take 2 isset failed.<br />';
else echo 'Take 2 isset passed.<br />';
unset($take2->key); // Silent Fail
echo 'Take 2: ' . $take2->key . '<br />';
foreach($take2 as $prop => $value) { // Silent fail, returns empty
	echo $prop . ':' . $value . '<br />';
}

class DynamicPropsTake3 extends StdClass { }

$dynamicProperties = new DynamicPropsTake3;
$dynamicProperties->name = 'Flexible';
echo $dynamicProperties->name;

?>