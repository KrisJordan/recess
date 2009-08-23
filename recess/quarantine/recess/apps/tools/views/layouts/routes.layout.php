<?php 
Layout::extend('layouts/master');
Layout::input($title, 'string');
Layout::input($body, 'Block');
Layout::input($scripts, 'Block', new HtmlBlock());

$title .= ' > Routes > ';

$navigation = Part::block('layouts/navigation', 'Routes');
?>