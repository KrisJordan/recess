<?php 
Layout::extend('layouts/master');
Layout::input($title, 'string');
Layout::input($body, 'Block');
Layout::input($scripts, 'Block', new HtmlBlock());

$title .= ' > Apps > ';
$navigation = Part::block('layouts/navigation', 'Apps');
?>