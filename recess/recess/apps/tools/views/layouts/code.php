<?php 
Layout::extend('layouts/master');

Layout::slotAppend('title', ' > Code > ');

Layout::block('navigation');
	Part::render('layouts/navigation', 'Code');
Layout::blockEnd() ;
?>