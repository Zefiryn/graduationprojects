<?php

class Application_Form_Diploma extends Zefir_Form
{

	protected $_languages;
	
	public function init()
	{
		$lang = new Application_Model_Languages();
		$this->_languages = $lang->fetchAll();
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
		
		$this->setDecorators(array(
			array('ViewScript', array('viewScript' => 'forms/_diplomaForm.phtml'))
		));

		$appSettings = Zend_Registry::get('appSettings');

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
		
		foreach($this->_languages as $lang)
		{
			$subform = new Application_Form_Diploma_Fields();
			$subform->getElement('lang_id')->setValue($lang->lang_id);
			$subform->removeDecorator('form');
			$subform->removeElement('csrf');
			$subform->removeElement('leave');
			$subform->removeElement('submit');
			$subform->removeDisplayGroup('submitFields');
			$subform->setIsArray(true);
			$this->addSubForm($subform, $lang->lang_code);
		}
		
		$this->_createCsrfElement();
		$this->_createStandardSubmit('submit');
		$this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
			->setDisplayGroupDecorators(array(
						'FormElements', 
						array('Fieldset', array('class' => 'submit'))
			));
	}


	public function getLanguages()
	{
		return $this->_languages;
	}
}

