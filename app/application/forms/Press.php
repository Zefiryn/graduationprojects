<?php

class Application_Form_Press extends Zefir_Form
{

	public function init()
	{
		parent::init();
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		
		$element = $this->createElement('hidden', 'element_id');
		$this->addElement($element);
		$options = Zend_Registry::get('options');
		$element = new Zend_Form_Element_File('element_path');
		$element->setDestination(APPLICATION_PATH.'/../public'.$options['upload']['cache'])
				->setAttribs(array('class' => 'file width1', 'style' => 'width: 450px;'))
				->setLabel('press_file')
				->setRequired(FALSE)
				->setAllowEmpty(TRUE)
				->addValidators(array(
					
				))
				->setDecorators(array(
					array('File'),
					array('ErrorMsg'),
					array('UnderDescription', array('class' => 'description', 'placement' => 'prepend')),
					array('MyLabel', array('placement' => 'prepend'))
				));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'element_description', array(
							'label' => 'press_description',
							'validators' => array(),
							'decorators' => $this->_getZefirDecorators(),
							'attribs' => array('class' => 'width1')
					));
		$this->addElement($element);
		
		$editions = Zend_Registry::get('edition_list');
		$element = $this->createElement('select', 'edition_id');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('edition')
				->setMultiOptions($editions )
				->setDecorators($this->_getStandardDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_NotEmpty(Zend_Validate_NotEmpty::ZERO),
					new Zend_Validate_Digits()
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

