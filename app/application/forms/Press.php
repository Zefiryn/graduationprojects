<?php

class Application_Form_Press extends Zefir_Form
{

	public function init()
	{
		parent::init();
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
		$element = $this->createElement('hidden', 'element_id');
		$this->addElement($element);
		$element = $this->createElement('text', 'element_description');
		$element->setLabel('press_description')
				->setRequired(true)
				->addValidators(array(new Zend_Validate_NotEmpty()))
				->setDecorators($this->_getZefirDecorators())
				->setAttribs(array('class' => 'width1'));
		$this->addElement($element);
		
		$options = Zend_Registry::get('options');
		$appSettings = Zend_Registry::get('appSettings');
		$element = new Zend_Form_Element_File('element_path');
		$element->setDestination(APPLICATION_PATH.'/../public'.$options['upload']['cache'])
				->setAttribs(array('class' => 'file width1', 'style' => 'width: 460px;'))
				->setLabel('press_file')
				->setRequired(true)
				->addValidators(array(
					array('Size', false, array('max' => $appSettings->max_file_size))					
				))
				->setDecorators(array(
					array('File'),
					array('ErrorMsg'),
					array('UnderDescription', array('class' => 'description', 'placement' => 'prepend')),
					array('MyLabel', array('placement' => 'prepend')),
					array('HtmlTag', array('tag' => 'div', 'class' => 'press_file'))
				));
		$this->addElement($element);
		
		$this->_createCsrfElement();
		$this->_createStandardSubmit('press_submit');
		$this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
			->setDisplayGroupDecorators(array(
						'FormElements', 
						array('Fieldset', array('class' => 'submit'))
		));
	}


}

