<?php

class Application_Form_Stage extends Zefir_Form
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
		
		$element = $this->createElement('hidden', 'stage_id')->setDecorators(array('ViewHelper'));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'stage_name');
		$element->setAttribs(array('class' => 'width1'))
			->setLabel('stage_name')
			->setDecorators($this->_getZefirDecorators())
			->setRequired(TRUE)
			->addValidators(array(
				new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
				new Zend_Validate_StringLength(array('min' => 1, 'max' => 60)),
			));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'stage_max_vote');
		$element->setAttribs(array('class' => 'width1'))
			->setLabel('stage_max_vote')
			->setDecorators($this->_getZefirDecorators())
			->setRequired(TRUE)
			->addValidators(array(
				new Zend_Validate_Digits()
			));
		$this->addElement($element);
		
		$element = $this->createElement('checkbox', 'active');
		$element->setAttribs(array('class' => 'checkbox'))
			->setLabel('active', array('tag' => 'label'))
			->setDecorators($this->_getStandardDecorators())
			->setRequired(FALSE)
			->addValidators(array(
				new Zend_Validate_Regex('/^0|1$/')
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