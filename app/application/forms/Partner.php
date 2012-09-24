<?php

class Application_Form_Partner extends Zefir_Form
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
		$this->setName('PartnerForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));
				
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		
		$element = $this->createElement('hidden', 'partner_id')->setDecorators(array('ViewHelper'));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'partner_name');
		$element->setAttribs(array('class' => 'width1'))
			->setLabel('partner_name')
			->setDecorators($this->_getZefirDecorators())
			->setRequired(TRUE)
			->addValidators(array(
				new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
				new Zend_Validate_StringLength(array('min' => 1, 'max' => 60)),
			));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'partner_link');
		$element->setAttribs(array('class' => 'width1'))
			->setLabel('partner_link')
			->setDecorators($this->_getZefirDecorators())
			->setRequired(TRUE)
			->addValidators(array(
				new Zefir_Validate_URL()
			));
		$this->addElement($element);
		
		$element = $this->createElement('select', 'partner_type');
		$element->setAttribs(array('class' => 'width1'))
			->setLabel('partner_type')
			->setDecorators($this->_getStandardDecorators())
			->setMultiOptions(array('organizer' => 'organizer', 'media' => 'media'));
		$this->addElement($element);
		
		$element = $this->createElement('hidden', 'partner_fileCache', array(
				'decorators' => array('ViewHelper')
		));
		$this->addElement($element);
		
		$options = Zend_Registry::get('options');
		$element = new Zend_Form_Element_File('partner_file');
		$element->setDestination(APPLICATION_PATH.'/../public'.$options['upload']['cache'])
						->setAttribs(array('class' => 'file width1'))
						->setLabel('partner_logo')
						->setRequired(true)
						->setAllowEmpty(TRUE)
						->addValidators(array(
								array('Extension', true, array(false, 'jpg,jpeg,png')),
								//not working on production server
								//array('MimeType', true, array(false, 'image')),
								array('ImageSize', false, array('minwidth' => 50,
										'maxwidth' => 800,
										'minheight' => 10,
										'maxheight' => 600))
						))
						->setDecorators(array(
								array('File'),
								array('ErrorMsg'),
								array('UnderDescription', array('class' => 'description', 'placement' => 'prepend')),
								array('MyLabel', array('placement' => 'prepend'))
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
