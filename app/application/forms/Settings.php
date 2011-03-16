<?php

class Application_Form_Settings extends Zefir_Form
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
		$this->setName('SettingsForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));
		
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		
		$edition = new Application_Model_Editions();
		$element = $this->createElement('select', 'current_edition');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('current_edition')
				->setDecorators($this->_getStandardDecorators())
				->setMultiOptions($edition->getEditions());	
		$this->addElement($element);
		
		$template = new Application_Model_TemplateSettings();
		$element = $this->createElement('select', 'template_default');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('default_template')
				->setDecorators($this->_getStandardDecorators())
				->setMultiOptions($template->getTemplates());	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'max_file_size');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('max_file_size')
				->setDescription('max_file_size_desc')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidator(new Zend_Validate_Regex('/^[0-9]+(,[0-9]+)?$/'));	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'date_format');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('date_format')
				->setRequired(TRUE)
				->setDecorators($this->_getZefirDecorators());	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'max_files');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('max_files')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidator(new Zend_Validate_Digits());	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'work_start_date');
		$element->setAttribs(array('class' => 'width1 date'))
				->setLabel('work_start_date')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zefir_Validate_Before('work_end_date')
				));	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'work_end_date');
		$element->setAttribs(array('class' => 'width1 date'))
				->setLabel('work_end_date')
				->setDecorators($this->_getZefirDecorators())
				->addValidators(array(
					new Zefir_Validate_Before('application_deadline')
				));	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'application_deadline');
		$element->setAttribs(array('class' => 'width1 date'))
				->setLabel('application_deadline')
				->setDecorators($this->_getZefirDecorators())
				->addValidators(array(
					new Zefir_Validate_Before('result_date')
				));	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'result_date');
		$element->setAttribs(array('class' => 'width1 date'))
				->setLabel('result_date')
				->setDecorators($this->_getZefirDecorators());	
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