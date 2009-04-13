<?php 
Layout::extend('master');
Layout::slotAppend('title', ' > Home > ');
Layout::block('navigation');
	Part::render('layouts/navigation', '');
Layout::blockEnd() ;
?>