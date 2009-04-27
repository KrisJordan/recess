<?php
Layout::extend('master');

Layout::slotAppend('title', '{{modelName}} - ');

Layout::block('navigation');
	Part::render('parts/navigation');
Layout::blockEnd();
?>