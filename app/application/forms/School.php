<?php

class Application_Form_School extends Zefir_Form
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
		$this->setName('SchoolForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));

		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');

		$element = $this->createElement('hidden', 'school_id');
		$this->addElement($element);

		$element = $this->createElement('text', 'school_name');
		$element->setAttribs(array('class' => 'width2'))
		->setLabel('school_name')
		->setDecorators($this->_getZefirDecorators())
		->setRequired(TRUE)
		->addValidators(array(
		new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
		new Zend_Validate_StringLength(array('min' => 0, 'max' => 60)),
		new Zefir_Validate_Unique(array(
        							'table' => 'schools',
        							'field' => 'school_name',
									'id' => 'school_id'))
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

