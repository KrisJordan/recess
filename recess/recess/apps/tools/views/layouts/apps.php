<?php 
Layout::extend('layouts/master');

Layout::slotAppend('title', ' > Apps > ');

Layout::block('navigation');
	Part::render('layouts/navigation', 'Apps');
Layout::blockEnd() ;
?>