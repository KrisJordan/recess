<?php
Part::input($htmlInput, 'PartBlock');
Part::input($label, 'string', '');
?><p><?php 
if($label != ''):
	?><label<?php 
	if(($id = $htmlInput->get('id')) != ''):	
	?> for="<?php echo $id ?>"<?php 
	endif ?>><?php echo $label ?></label><br /><?php 
else: 
	echo '&nbsp;'; 
endif; 

echo $htmlInput;
?></p>