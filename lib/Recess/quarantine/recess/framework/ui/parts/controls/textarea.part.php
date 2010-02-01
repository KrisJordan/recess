<?php
Part::input($control,'Control');
Part::input($name,	'string');
Part::input($value, 'string',	'');
Part::input($label, 'string',	'');
Part::input($attrs, 'HtmlAttributes', new HtmlAttributes());
Part::input($classes,'HtmlClasses', new HtmlClasses('control-textarea'));

$controlBlock  = Part::block('html/textarea',
						$control->getId(),
						$control->getFormName(),
						$value,
						$attrs,
						$classes);
						
Part::draw( $control->getSkin(),
			$controlBlock,
			$label);
?>