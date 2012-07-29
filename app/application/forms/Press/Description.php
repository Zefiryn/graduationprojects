<?php

class Application_Form_Press_Description extends Zefir_Form
{

	public function init()
	{
		$L = $this->_regex['L'];
		$N = $this->_regex['N'];
		$S = $this->_regex['S'];
		$E = $this->_regex['E'];
		$B = $this->_regex['B'];
		parent::init();
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		 
		$element= $this->createElement('hidden', 'element_id');
		$element->setDecorators(array('ViewHelper'));
		$this->addElement($element);

		$element= $this->createElement('hidden', 'element_type');
		$element->setDecorators(array('ViewHelper'))
				->setValue('description');
		$this->addElement($element);

		$this->addElement($element);

		$element= $this->createElement('textarea', 'element__desscription');
		$element->setLabel('press_description')
				->setAttribs(array('class' => 'width1 tinymce'))
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					//new Zend_Validate_Regex('/^['.$L.$N.$S.$E.$B.'\ ]*$/')
				));
		$this->addElement($element);

		$this->_createCsrfElement();
		$this->_createStandardSubmit('press_submit');
		$this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
		->setDisplayGroupDecorators(array(
						'FormElements', 
		array('Fieldset', array('class' => 'submit'))
		));;
	}

}

