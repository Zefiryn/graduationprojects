<?php

class Application_Form_Diploma extends Zefir_Form
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
		$this->setName('DiplomaForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));

		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');


		$appSettings = Zend_Registry::get('appSettings');
		$element = $this->createElement('hidden', 'lang_id');
		$element->setDecorators(array('ViewHelper'));
		$this->addElement($element);

		$element = $this->createElement('hidden', 'diploma_id');
		$element->setDecorators(array('ViewHelper'));
		$this->addElement($element);

		$element = $this->createElement('text', 'name');
		$element->setAttribs(array('class' => 'width1'))
		->setLabel('name')
		->setDecorators($this->_getZefirDecorators())
		->setRequired(TRUE)
		->addValidators(array(
		new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
		new Zend_Validate_StringLength(array('min' => 3, 'max' => 300))
		));
		$this->addElement($element);

		$element = $this->createElement('text', 'surname');
		$element->setAttribs(array('class' => 'width1'))
		->setLabel('surname')
		->setDecorators($this->_getZefirDecorators())
		->setRequired(TRUE)
		->addValidators(array(
		new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
		new Zend_Validate_StringLength(array('min' => 3, 'max' => 300))
		));
		$this->addElement($element);

		$element = $this->createElement('text', 'email');
		$element->setAttribs(array('class' => 'width1'))
		->setLabel('email')
		->setDecorators($this->_getZefirDecorators())
		->setRequired(TRUE)
		->addValidators(array(
		new Zend_Validate_EmailAddress(),
		new Zend_Validate_StringLength(array('min' => 3, 'max' => 35))
		));
		$this->addElement($element);

		$element = $this->createElement('text', 'supervisor');
		$element->setAttribs(array('class' => 'width1'))
		->setLabel('supervisor')
		->setDecorators($this->_getZefirDecorators())
		->setRequired(FALSE)
		->addValidators(array(
		new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
		new Zend_Validate_StringLength(array('min' => 3, 'max' => 300))
		));
		$this->addElement($element);

		$element = $this->createElement('text', 'supervisor_degree');
		$element->setAttribs(array('class' => 'width1'))
		->setLabel('supervisor_degree')
		->setDecorators($this->_getZefirDecorators())
		->setRequired(FALSE)
		->addValidators(array(
		new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
		new Zend_Validate_StringLength(array('max' => 300))
		));
		$this->addElement($element);

		$degree = new Application_Model_Degrees();
		$element = $this->createElement('select', 'degree_id');
		$element->setAttribs(array('class' => 'width1'))
		->setLabel('degree')
		->setMultiOptions($degree->getDegreesList())
		->setDecorators($this->_getStandardDecorators())
		->setRequired(FALSE)
		->addValidators(array(
			
		new Zend_Validate_Digits()
		));
		$this->addElement($element);

		$fields = new Application_Form_Diploma_Fields();
		$this->addSubForm($fields, 'fields');

		$this->_createCsrfElement();
		$this->_createStandardSubmit('submit');
		$this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
		->setDisplayGroupDecorators(array(
						'FormElements', 
		array('Fieldset', array('class' => 'submit'))
		));
	}


}

