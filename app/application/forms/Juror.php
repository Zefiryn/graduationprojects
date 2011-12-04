<?php

class Application_Form_Juror extends Zefir_Form
{

	public function init()
	{
		$L = $this->_regex['L'];
		$N = $this->_regex['N'];
		$S = $this->_regex['S'];
		$E = $this->_regex['E'];
		$B = $this->_regex['B'];
		parent::init();
		
		$this->setMethod('post');
		$this->setName('UserForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));
		
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		
		$element = $this->createElement('hidden', 'juror_id')->setDecorators(array('ViewHelper'));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'juror_name');
		$element->setAttribs(array('class' => 'width1'))
			->setLabel('juror_name')
			->setDecorators($this->_getZefirDecorators())
			->setRequired(TRUE)
			->addValidators(array(
				new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
				new Zend_Validate_StringLength(array('min' => 3, 'max' => 60)),
				new Zefir_Validate_Unique(array(
		        							'table' => 'jurors',
		        							'field' => 'juror_name',
		        							'id' => 'juror_id'))
			));
		$this->addElement($element);
		
		$country = array('pl' => 'Poland', 'sk' => 'Slovakia', 'cs' => 'Czech Republic', 'hu' => 'Hungary');
		$element = $this->createElement('select', 'country');
		$element->setAttribs(array('class' => 'width1', 'size' => 1))
			->setLabel('country')
			->setDecorators($this->_getStandardDecorators())
			->setMultiOptions($country)
			->setRequired(TRUE);
		$this->addElement($element);
		
		$element = $this->createElement('text', 'wage');
		$element->setAttribs(array('class' => 'width1'))
			->setLabel('wage')
			->setDecorators($this->_getZefirDecorators())
			->setRequired(TRUE)
			->addValidators(array(
				new Zend_Validate_Digits()
			));
		$this->addElement($element);
		
		$this->_createCsrfElement();
		$this->_createStandardSubmit('submit');
		$this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
			->setDisplayGroupDecorators(array(
								'FormElements', 
								array('Fieldset', array('class' => 'submit'))
			));
	}
}