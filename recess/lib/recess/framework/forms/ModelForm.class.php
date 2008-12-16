<?php
Library::import('recess.framework.forms.Form');

class ModelForm extends Form {
	
	function __construct($name, Model $model = null, $formValues = array()) {
		if($model != null) {
			$properties = Model::getProperties($model);
			
			foreach($properties as $property) {
				$property = new ModelProperty();
				$propertyName = $property->name;
				$input = new FormInput($this->name . '[' . $propertyName . ']', $property->type);
				if(isset($formValues[$propertyName])) {
					$input->setValue($formValues[$propertyName]);
					$model->$propertyName = $input->getValue();
				}
				$this->inputs[] = $input;
			}
		}
	}
	
	function handle(ModelValidationException $exception) {
		
	}
	
}
?>