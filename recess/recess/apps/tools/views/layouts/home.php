<?php 
Layout::extend('layouts/master');
Layout::slotAppend('title', ' > Home > ');
Layout::block('navigation');
	Part::render('layouts/navigation', '');
Layout::blockEnd() ;
?>