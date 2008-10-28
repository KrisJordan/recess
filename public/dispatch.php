<?php

require_once('../lib/Recess.php');
Library::addClassPath('../'); // base directory of 'application' folder
Library::import('recess.Coordinator');
Coordinator::coordinate();

?>