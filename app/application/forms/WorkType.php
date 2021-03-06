<?php

class Application_Form_WorkType extends Zefir_Form
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

		$element = $this->createElement('hidden', 'work_type_id');
		$this->addElement($element);

		$element = $this->createElement('text', 'work_type_name');
		$element->setAttribs(array('class' => 'width2'))
		->setLabel('work_type_name')
		->setDecorators($this->_getZefirDecorators())
		->setRequired(TRUE)
		->addValidators(array(
		new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
		new Zend_Validate_StringLength(array('min' => 0, 'max' => 10)),
		new Zefir_Validate_Unique(array(
        							'table' => 'work_types',
        							'field' => 'work_type_name',
									'id' => 'work_type_id'))
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