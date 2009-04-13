<?php 
Layout::extend('layouts/master');

Layout::slotAppend('title', ' > Database > ');

Layout::block('navigation');
	Part::render('layouts/navigation', 'Database');
Layout::blockEnd() ;
?>