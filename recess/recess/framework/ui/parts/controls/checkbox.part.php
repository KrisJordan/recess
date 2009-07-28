<?php
Part::input($control,'Control');
Part::input($name,	'string');
Part::input($value, 'string',	'__false__');
Part::input($label, 'string',	'');
Part::input($attrs, 'HtmlAttributes', new HtmlAttributes());
Part::input($classes,'HtmlClasses', new HtmlClasses('control-text'));

if($value == '__true__') {
	$attrs->set('checked','checked');
}
$value = '__true__';

$controlBlock  = Part::block('html/input',
						'checkbox',
						$control->getId(),
						$control->getFormName(),
						$value,
						$attrs,
						$classes);
						
Part::draw( $control->getSkin(),
			$controlBlock,
			$label);
?>