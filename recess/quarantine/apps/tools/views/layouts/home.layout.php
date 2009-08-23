<?php 
Layout::extend('layouts/master');
Layout::input($body, 'Block');
Layout::input($title, 'string');

$title .= ' > Home > ';
$navigation = Part::block('layouts/navigation', '');
?>