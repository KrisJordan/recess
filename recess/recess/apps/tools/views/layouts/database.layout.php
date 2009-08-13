<?php 
Layout::extend('layouts/master');
Layout::input($title, 'string');
Layout::input($body, 'Block');
Layout::input($scripts, 'Block', new HtmlBlock());


$title .= ' > Database > ';

$navigation = Part::block('layouts/navigation', 'Database');
?>