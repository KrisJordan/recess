<?php
Part::input($optional, 'string', 'default');
if($optional == 'default') { echo 'default'; }
else { echo 'non-default'; }
?>