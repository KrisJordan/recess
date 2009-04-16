<?php 
Layout::extend('layouts/master');

Layout::slotAppend('title', ' > Routes > ');

Layout::block('navigation');
	Part::render('layouts/navigation', 'Routes');
Layout::blockEnd() ;
?>